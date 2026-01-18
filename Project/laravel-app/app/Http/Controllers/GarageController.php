<?php

namespace App\Http\Controllers;

use App\Models\Garage;
use App\Http\Requests\StoreGarageRequest;
use App\Http\Requests\UpdateGarageRequest;

class GarageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $garages = Garage::all();
        return view('garages.index', compact('garages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGarageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Garage $garage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Garage $garage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGarageRequest $request, Garage $garage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Garage $garage)
    {
        //
    }
}
