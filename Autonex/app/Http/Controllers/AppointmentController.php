<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    private function ensureAppointmentOwnership(Appointment $appointment): void
    {
        if (!$this->isAdmin() && $appointment->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function userCarsQuery()
    {
        $query = Car::orderBy('make_model');

        if (!$this->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Appointment::with(['car', 'user'])->latest();

        if (!$this->isAdmin()) {
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
        $cars = $this->userCarsQuery()->get();

        return view('appointments.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);

        if (!$this->isAdmin()) {
            $ownsCar = Car::where('id', $validated['car_id'])
                ->where('user_id', $userId)
                ->exists();

            if (!$ownsCar) {
                abort(403);
            }
        }

        $exists = Appointment::where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('status', 'confirmed')
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'time' => 'Erre az időpontra már van megerősített foglalás',
                ]);
        }

        Appointment::create([
            ...$validated,
            'user_id' => $userId,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Időpont sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->ensureAppointmentOwnership($appointment);

        $appointment->load(['car', 'user']);

        return view('appointments.show', compact('appointment'));
    }

}
