@extends('layouts.app')

@section('content')

<section class="cars-hero cars-hero-tight">
    <p class="cars-kicker">Edit Car</p>
    <h1 class="page-title">Autó szerkesztése</h1>
</section>

<div class="cars-split-layout">
    <aside class="card cars-side-panel">
        <h3>Jelenlegi autó</h3>
        <p><strong>Típus:</strong> {{ $car->make_model }}</p>
        <p><strong>VIN:</strong> {{ $car->vin ?? 'n/a' }}</p>
        <p><strong>Év:</strong> {{ $car->year ?? 'n/a' }}</p>
    </aside>

    <div class="card form-card car-form-card">
        <form action="{{ route('cars.update', $car) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="make_model">Típus</label>
            <input id="make_model" type="text" name="make_model" value="{{ old('make_model', $car->make_model) }}">
            @error('make_model')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="vin">VIN</label>
            <input id="vin" type="text" name="vin" value="{{ old('vin', $car->vin) }}">
            @error('vin')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label for="year">Év</label>
            <input id="year" type="number" name="year" value="{{ old('year', $car->year) }}">
            @error('year')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <div class="form-actions">
                <button class="btn car-btn-main" type="submit">Frissítés</button>
                <a href="{{ route('cars.show', $car) }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</div>

@endsection