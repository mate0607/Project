<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    // Jogosultsag vizsgalat: admin felhasznalo tobb adathoz ferhet hozza.
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    // A sajat user azonosito lekerese egy helyre kerul a jobb olvashatosagert.
    private function currentUserId(): ?int
    {
        return auth()->id();
    }

    // Megtekintesnel csak tulajdonos vagy admin lathatja az adott idopontot.
    private function ensureAppointmentOwnership(Appointment $appointment): void
    {
        if (!$this->isAdmin() && $appointment->user_id !== $this->currentUserId()) {
            abort(403);
        }
    }

    // Itt keszul a valaszthato auto lista: usernel sajat, adminnal teljes.
    private function userCarsQuery()
    {
        $query = Car::orderBy('make_model');

        if (!$this->isAdmin()) {
            $query->where('user_id', $this->currentUserId());
        }

        return $query;
    }

    // Tarolt idopont adatok validalasa.
    private function validateStoreData(Request $request): array
    {
        return $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);
    }

    // Nem admin felhasznalo csak a sajat autojara foglalhat idopontot.
    private function ensureCarOwnershipById(int $carId, int $userId): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $ownsCar = Car::where('id', $carId)
            ->where('user_id', $userId)
            ->exists();

        if (!$ownsCar) {
            abort(403);
        }
    }

    // Ugyanarra az idopontra csak egy megerositett foglalas lehet.
    private function hasConfirmedConflict(string $date, string $time): bool
    {
        return Appointment::where('date', $date)
            ->where('time', $time)
            ->where('status', 'confirmed')
            ->exists();
    }

    // Foglalasi utkozes eseten ugyanazt a validacios hibat dobjuk vissza, mint korabban.
    private function throwTimeConflictValidationError(): void
    {
        throw ValidationException::withMessages([
            'time' => 'Erre az időpontra már van megerősített foglalás',
        ]);
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
        $userId = $this->currentUserId();

        if (!$userId) {
            return redirect()->route('login');
        }

        $validated = $this->validateStoreData($request);

        $this->ensureCarOwnershipById((int) $validated['car_id'], $userId);

        try {
            if ($this->hasConfirmedConflict($validated['date'], $validated['time'])) {
                $this->throwTimeConflictValidationError();
            }
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->errors());
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

        $appointment->load(['car', 'user', 'servicePhotos']);

        return view('appointments.show', compact('appointment'));
    }

}
