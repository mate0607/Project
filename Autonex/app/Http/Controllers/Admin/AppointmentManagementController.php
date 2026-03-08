<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Car;
use Illuminate\Http\Request;

class AppointmentManagementController extends Controller
{
    // Az admin gyors statusz-modositasnal engedett ertekek listaja.
    private const QUICK_UPDATE_STATUSES = 'confirmed,cancelled,completed';

    public function index()
    {
        // Az index oldalon egyszerre kell a listazott adathalmaz es a fejléc statisztika is.
        $appointments = Appointment::with(['user', 'car'])
            ->latest()
            ->get();

        $pendingCount = Appointment::where('status', 'pending')->count();
        $confirmedCount = Appointment::where('status', 'confirmed')->count();

        return view('admin.appointments.index', compact('appointments', 'pendingCount', 'confirmedCount'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['user', 'car']);
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

        $appointment->update($validated);

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

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Státusz sikeresen módosítva.');
    }

    private function hasConfirmedConflict(string $date, string $time, ?int $ignoreId = null): bool
    {
        // Utközésnek tekintjuk, ha ugyanarra a datumra+idore mar van megerositett foglalas.
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
