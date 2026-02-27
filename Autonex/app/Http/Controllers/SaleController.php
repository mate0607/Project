<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use App\Models\User;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['car', 'buyer', 'seller'])->latest()->get();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::orderBy('make_model')->get();
        $users = User::orderBy('name')->get();

        return view('sales.create', compact('cars', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login');
        }

        Sale::create([
            ...$request->validated(),
            'seller_id' => $userId,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Eladás sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['car', 'buyer', 'seller']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $cars = Car::orderBy('make_model')->get();
        $users = User::orderBy('name')->get();

        return view('sales.edit', compact('sale', 'cars', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $sale->update($request->validated());

        return redirect()->route('sales.index')
            ->with('success', 'Eladás sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Eladás törölve!');
    }
}
