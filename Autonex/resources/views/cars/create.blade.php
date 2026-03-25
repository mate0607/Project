@extends('layouts.app')

@section('content')

<section class="car-create-wrap">
    <header class="car-create-head">
        <p class="car-create-kicker">Gépjármű hozzáadása</p>
        <h1 class="page-title car-create-title">Új autó</h1>
        <p class="car-create-subtitle">Add meg a járműved adatait.</p>
    </header>

    <div class="card car-form-card car-create-card">
        <form method="POST" action="{{ route('cars.store') }}" class="car-create-form">
            @csrf

            <div class="car-grid">
                <div class="car-field car-field-full">
                    <label for="make_model">Típus (márka + modell)</label>
                    <input id="make_model" type="text" name="make_model" value="{{ old('make_model') }}" placeholder="pl. Toyota Corolla">
                    @error('make_model')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="car-field">
                    <label for="license_plate">Rendszám</label>
                    <input id="license_plate" type="text" name="license_plate" value="{{ old('license_plate') }}" placeholder="pl. ABC-123">
                    @error('license_plate')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="car-field">
                    <label for="year">Évjárat</label>
                    <input id="year" type="number" name="year" value="{{ old('year') }}" placeholder="pl. 2020">
                    @error('year')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="form-actions car-actions">
                <button class="btn car-btn-main" type="submit">Mentés</button>
                <a href="{{ route('cars.index') }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</section>



@endsection