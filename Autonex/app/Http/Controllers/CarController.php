<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Controllers\Traits\AdminHelpers;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;

class CarController extends Controller
{
    use AdminHelpers;

    // Nem admin felhasznalo csak a sajat autojan vegezhet muveletet.
    private function ensureCarOwnership(Car $car): void
    {
        if (!$this->isAdmin() && $car->user_id !== $this->currentUserId()) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Car::with('appointments')->withCount(['messages as unread_messages_count' => function ($q) {
            $q->where('receiver_id', auth()->id())->where('is_read', false);
        }]);

        if (!$this->isAdmin()) {
            $query->where('user_id', $this->currentUserId());
        }

        $cars = $query->latest()->get();

        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $userId = $this->currentUserId();

        if (!$userId) {
            return redirect()->route('login');
        }

        Car::create([
            ...$request->validated(),
            'user_id' => $userId,
        ]);

        return redirect()->route('cars.index')
            ->with('success', 'Autó sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $this->ensureCarOwnership($car);

        return view('cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $this->ensureCarOwnership($car);

        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        $this->ensureCarOwnership($car);

        $car->update($request->validated());

        return redirect()->route('cars.index')
            ->with('success', 'Autó sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $this->ensureCarOwnership($car);

        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Autó törölve!');
    }
}