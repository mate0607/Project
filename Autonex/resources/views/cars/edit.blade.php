@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Autó szerkesztése</h1>
        <p>Frissítsd a jármű adatait.</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('cars.update', $car) }}">
            @csrf
            @method('PUT')

            <div class="anx-grid anx-grid--2">
                <div class="anx-field anx-field--full">
                    <label for="make_model">Típus (márka + modell)</label>
                    <input id="make_model" type="text" name="make_model" value="{{ old('make_model', $car->make_model) }}" placeholder="pl. Toyota Yaris">
                    @error('make_model') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="license_plate">Rendszám</label>
                    <input id="license_plate" type="text" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" placeholder="pl. ABC-123">
                    @error('license_plate') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="year">Évjárat</label>
                    <input id="year" type="number" name="year" value="{{ old('year', $car->year) }}" placeholder="pl. 2022">
                    @error('year') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Módosítások mentése</button>
                <a href="{{ route('cars.show', $car) }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection