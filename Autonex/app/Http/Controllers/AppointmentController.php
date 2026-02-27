<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Appointment::with(['car', 'user'])->latest();

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        $appointments = $query->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::orderBy('make_model')->get();

        return view('appointments.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login');
        }

        Appointment::create([
            ...$request->validated(),
            'user_id' => $userId,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Időpont sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        if (auth()->user()->role !== 'admin' && $appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load(['car', 'user']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $cars = Car::orderBy('make_model')->get();

        return view('appointments.edit', compact('appointment', 'cars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.index')
            ->with('success', 'Időpont sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Időpont törölve!');
    }
}
