<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\ServicePhoto;
use Illuminate\Http\Request;

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

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'car']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['user', 'car', 'servicePhotos']);
        $cars = Car::orderBy('make_model')->get();

        // Mechanic pool: pick a random mechanic if none assigned
        $mechanicPool = self::MECHANIC_POOL;

        return view('admin.appointments.edit', compact('appointment', 'cars', 'mechanicPool'));
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

        $photoFile = $request->file('photo');
        $photoTitle = $validated['photo_title'] ?? null;
        unset($validated['photo'], $validated['photo_title']);

        $appointment->update($validated);

        if ($appointment->status === 'completed' && $appointment->service_stage === 'ready' && $appointment->user_id) {
            $car = $appointment->car;
            $this->sendReadyNotification($appointment->user_id, $car);
        }

        if ($photoFile) {
            $path = $photoFile->store('service-photos', 'public');
            ServicePhoto::create([
                'appointment_id' => $appointment->id,
                'title' => $photoTitle ?: 'Szerviz fotó',
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
        $storagePath = storage_path('app/public/' . $photo->path);
        if (file_exists($storagePath)) {
            unlink($storagePath);
        }

        $photo->delete();

        return back()->with('success', 'Fotó törölve.');
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
