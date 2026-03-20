@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cars-ui.css') }}">
@endpush

@section('content')

<section class="cars-shell cars-page-enter">
    <header class="cars-topbar">
        <div>
            <p class="cars-kicker">Edit Vehicle</p>
            <h1 class="page-title">Autó szerkesztése</h1>
            <p class="page-subtitle">Frissítsd a jármű profiladatait gyorsan és biztonságosan.</p>
        </div>
        <a href="{{ route('cars.show', $car) }}" class="btn btn-muted">Vissza a profilhoz</a>
    </header>

    <div class="cars-edit-layout cars-page-enter" style="--enter-delay: 0.04s;">
        <article class="card car-form-card">
            <form action="{{ route('cars.update', $car) }}" method="POST" class="car-edit-form">
                @csrf
                @method('PUT')

                <div class="field-group">
                    <label for="make_model">Típus</label>
                    <input id="make_model" type="text" name="make_model" value="{{ old('make_model', $car->make_model) }}" placeholder="pl. Toyota Yaris">
                    @error('make_model')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="vin">VIN</label>
                    <input id="vin" type="text" name="vin" value="{{ old('vin', $car->vin) }}" placeholder="pl. JTDKB20U793123456">
                    @error('vin')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="license_plate">Rendszám</label>
                    <input id="license_plate" type="text" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" placeholder="pl. ABC-123">
                    @error('license_plate')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="year">Év</label>
                    <input id="year" type="number" name="year" value="{{ old('year', $car->year) }}" placeholder="pl. 2022">
                    @error('year')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="btn car-btn-main" type="submit">Módosítások mentése</button>
                    <a href="{{ route('cars.show', $car) }}" class="btn btn-muted">Mégse</a>
                </div>
            </form>
        </article>

        <aside class="card cars-side-panel car-preview-panel">
            <h3>
                <span class="car-detail-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 16H9m10 0h2m-1-4 1 4-1 4h-1M3 16h2m0 0h4m-4 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2Zm14 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2ZM5 16l1.3-5.1A2 2 0 0 1 8.24 9.4h6.52a2 2 0 0 1 1.94 1.5L18 16"></path>
                    </svg>
                </span>
                <span>Jelenlegi állapot</span>
            </h3>

            <div class="car-preview-list">
                <div>
                    <p>Típus</p>
                    <strong>{{ $car->make_model }}</strong>
                </div>
                <div>
                    <p>VIN</p>
                    <strong>{{ $car->vin ?? 'n/a' }}</strong>
                </div>
                <div>
                    <p>Rendszám</p>
                    <strong>{{ $car->license_plate ?? 'n/a' }}</strong>
                </div>
                <div>
                    <p>Év</p>
                    <strong>{{ $car->year ?? 'n/a' }}</strong>
                </div>
            </div>
        </aside>
    </div>
</section>

@endsection