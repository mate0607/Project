<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\ServicePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentManagementController extends Controller
{
    private const QUICK_UPDATE_STATUSES = 'confirmed,cancelled,completed';

    private const MECHANIC_POOL = [
        'Kovács István', 'Nagy Péter', 'Szabó Gábor', 'Tóth László', 'Horváth Zoltán',
        'Varga Tamás', 'Kiss András', 'Molnár Ferenc', 'Németh Attila', 'Farkas Béla',
        'Balogh Károly', 'Papp Dániel', 'Takács Mihály', 'Simon József', 'Rácz Tibor',
        'Lakatos Róbert', 'Mészáros Imre', 'Oláh Sándor', 'Fekete Márton', 'Szilágyi Ádám',
    ];

    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'car'])->latest();

        if ($request->filled('filter_name')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%' . $request->filter_name . '%'));
        }
        if ($request->filled('filter_car')) {
            $query->whereHas('car', fn ($q) => $q->where('make_model', 'like', '%' . $request->filter_car . '%'));
        }
        if ($request->filled('filter_plate')) {
            $query->whereHas('car', fn ($q) => $q->where('license_plate', 'like', '%' . $request->filter_plate . '%'));
        }
        if ($request->filled('filter_date')) {
            $query->where('date', $request->filter_date);
        }

        $appointments = $query->get();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'car_brand'      => ['required', 'string', 'max:255'],
            'car_model'      => ['required', 'string', 'max:255'],
            'car_year'       => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'car_engine'     => ['required', 'string', 'max:255'],
            'car_fuel_type'  => ['required', 'string', 'max:100'],
            'date'           => ['required', 'date'],
            'time'           => ['required', 'date_format:H:i'],
            'service'        => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->hasConfirmedConflict($validated['date'], $validated['time'])) {
            return back()
                ->withInput()
                ->withErrors(['time' => 'Erre a dátumra és időpontra már van megerősített foglalás.']);
        }

        $validated['status'] = 'pending';

        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Időpont sikeresen létrehozva.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'car']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['user', 'car', 'servicePhotos']);
        $cars = Car::orderBy('make_model')->get();

        return view('admin.appointments.edit', compact('appointment', 'cars'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $validated = $request->validated();

        if (
            $validated['status'] === 'confirmed'
            && $this->hasConfirmedConflict($validated['date'], $validated['time'], $appointment->id)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'time' => 'Erre a dátumra és időpontra már van megerősített foglalás.',
                ]);
        }

        if (empty($validated['service_stage'])) {
            $validated['service_stage'] = null;
        }

        $photoFiles = $request->file('photos', []);
        unset($validated['photos'], $validated['photo'], $validated['photo_title']);

        $appointment->update($validated);

        if ($appointment->status === 'completed' && $appointment->service_stage === 'ready' && $appointment->user_id) {
            $car = $appointment->car;
            $this->sendReadyNotification($appointment->user_id, $car);
        }

        foreach ($photoFiles as $photoFile) {
            $path = $photoFile->store('service-photos', 'public');
            ServicePhoto::create([
                'appointment_id' => $appointment->id,
                'title' => 'Szerviz fotó',
                'path' => $path,
            ]);
        }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Időpont sikeresen frissítve.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . self::QUICK_UPDATE_STATUSES],
        ]);

        if (
            $validated['status'] === 'confirmed'
            && $this->hasConfirmedConflict($appointment->date, $appointment->time, $appointment->id)
        ) {
            return back()->withErrors([
                'status' => 'Erre a dátumra és időpontra már van megerősített foglalás.',
            ]);
        }

        $appointment->update([
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'completed' && $appointment->service_stage === 'ready' && $appointment->user_id) {
            $appointment->load('car');
            $this->sendReadyNotification($appointment->user_id, $appointment->car);
        }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Státusz sikeresen módosítva.');
    }

    public function destroyPhoto(ServicePhoto $photo)
    {
        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        return back()->with('success', 'Fotó törölve.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Időpont sikeresen törölve.');
    }

    private function sendReadyNotification(int $userId, ?Car $car): void
    {
        $makeModel = $car?->make_model ?? 'Ismeretlen autó';
        $vin = $car?->vin ?? '';

        AdminNotification::create([
            'user_id' => $userId,
            'title' => 'Szerviz kész — ' . $makeModel,
            'message' => 'Az Ön autója (' . $makeModel . ($vin ? ', ' . $vin : '') . ') elkészült és átvehető. Kérjük, egyeztessen időpontot az átvételhez.',
        ]);
    }

    private function hasConfirmedConflict(string $date, string $time, ?int $ignoreId = null): bool
    {
        return Appointment::query()
            ->where('date', $date)
            ->where('time', $time)
            ->where('status', 'confirmed')
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }
}
