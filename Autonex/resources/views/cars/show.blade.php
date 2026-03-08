@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cars-ui.css') }}">
@endpush

@section('content')

<section class="cars-shell cars-page-enter">
    <article class="car-profile-hero">
        <div class="car-profile-main">
            <span class="car-detail-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 16H9m10 0h2m-1-4 1 4-1 4h-1M3 16h2m0 0h4m-4 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2Zm14 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2ZM5 16l1.3-5.1A2 2 0 0 1 8.24 9.4h6.52a2 2 0 0 1 1.94 1.5L18 16"></path>
                </svg>
            </span>

            <div>
                <p class="cars-kicker">Vehicle Profile</p>
                <h1 class="page-title car-detail-title">{{ $car->make_model }}</h1>
                <div class="car-meta-chips">
                    <span>VIN: {{ $car->vin ?? 'Nincs megadva' }}</span>
                    <span>Év: {{ $car->year ?? 'Nincs megadva' }}</span>
                    <span>ID: {{ $car->id }}</span>
                </div>
            </div>
        </div>

        <div class="car-profile-actions">
            <a href="{{ route('cars.index') }}" class="btn btn-muted">Vissza a listára</a>
            <a href="{{ route('cars.edit', $car) }}" class="btn car-btn-main">Szerkesztés</a>
        </div>
    </article>

    <section class="car-profile-grid cars-page-enter" style="--enter-delay: 0.04s;">
        <article class="car-info-card car-info-card-large">
            <p class="car-info-label">Típus</p>
            <strong class="car-info-value">{{ $car->make_model }}</strong>
            <p class="car-info-note">A jármű fő profiladata.</p>
        </article>

        <article class="car-info-card">
            <p class="car-info-label">VIN</p>
            <strong class="car-info-value">{{ $car->vin ?? 'Nincs megadva' }}</strong>
            <p class="car-info-note">Egyedi járműazonosító.</p>
        </article>

        <article class="car-info-card">
            <p class="car-info-label">Gyártási év</p>
            <strong class="car-info-value">{{ $car->year ?? 'Nincs megadva' }}</strong>
            <p class="car-info-note">Évjárat szerinti besorolás.</p>
        </article>

        <article class="car-info-card">
            <p class="car-info-label">Rendszer azonosító</p>
            <strong class="car-info-value">#{{ $car->id }}</strong>
            <p class="car-info-note">Belső rekord azonosító.</p>
        </article>
    </section>
</section>

@endsection