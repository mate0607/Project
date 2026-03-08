@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cars-ui.css') }}">
@endpush

@section('content')

    @php($latestYear = $cars->whereNotNull('year')->max('year'))

    <section class="cars-shell cars-page-enter">
        <header class="cars-topbar">
            <div>
                <h1 class="page-title">Jármű dashboard</h1>
                <p class="page-subtitle">Minden autód egy átlátható, gyorsan kezelhető felületen.</p>
            </div>
            <a href="{{ route('cars.create') }}" class="btn car-btn-main">
                <span class="ui-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                </span>
                <span>Új autó hozzáadása</span>
            </a>
        </header>

        <section class="cars-kpi-grid" aria-label="Flotta statisztikák">
            <article class="cars-kpi-card">
                <p>Összes autó</p>
                <strong>{{ $cars->count() }}</strong>
            </article>
            <article class="cars-kpi-card">
                <p>VIN nélküli</p>
                <strong>{{ $cars->whereNull('vin')->count() }}</strong>
            </article>
            <article class="cars-kpi-card">
                <p>Legfrissebb évjárat</p>
                <strong>{{ $latestYear ?? '—' }}</strong>
            </article>
        </section>

        @if($cars->count() > 0)
            <section class="cars-featured">
                <article class="cars-featured-card">
                    <div>
                        <p class="cars-featured-label">Kiemelt jármű</p>
                        <h2>{{ $cars->first()->make_model }}</h2>
                        <p class="cars-featured-meta">
                            VIN: {{ $cars->first()->vin ?? 'Nincs megadva' }}
                            <span>•</span>
                            Év: {{ $cars->first()->year ?? 'Nincs megadva' }}
                        </p>
                    </div>
                    <div class="cars-featured-actions">
                        <a href="{{ route('cars.show', $cars->first()) }}" class="car-btn-secondary">
                            <span class="ui-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.06 12.34a1 1 0 0 1 0-.68 10 10 0 0 1 19.88 0 1 1 0 0 1 0 .68 10 10 0 0 1-19.88 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                            <span>Megnyit</span>
                        </a>
                        <a href="{{ route('cars.edit', $cars->first()) }}" class="car-btn-secondary">
                            <span class="ui-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="m16.5 3.5 4 4L7 21H3v-4L16.5 3.5z"></path>
                                </svg>
                            </span>
                            <span>Szerkeszt</span>
                        </a>
                    </div>
                </article>
            </section>
        @endif

        <section class="cars-card-grid" aria-label="Saját autók listája">
            @forelse($cars as $car)
                <article class="car-entry-card">
                    <div class="car-entry-head">
                        <span class="car-entry-chip">#{{ $car->id }}</span>
                        <h3>{{ $car->make_model }}</h3>
                    </div>

                    <div class="car-entry-meta">
                        <div>
                            <span>VIN</span>
                            <strong>{{ $car->vin ?? 'Nincs megadva' }}</strong>
                        </div>
                        <div>
                            <span>Év</span>
                            <strong>{{ $car->year ?? 'Nincs megadva' }}</strong>
                        </div>
                    </div>

                    <div class="car-entry-actions">
                        <a href="{{ route('cars.show', $car) }}" class="car-btn-secondary">
                            <span class="ui-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.06 12.34a1 1 0 0 1 0-.68 10 10 0 0 1 19.88 0 1 1 0 0 1 0 .68 10 10 0 0 1-19.88 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                            <span>Megnyit</span>
                        </a>
                        <a href="{{ route('cars.edit', $car) }}" class="car-btn-secondary">
                            <span class="ui-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="m16.5 3.5 4 4L7 21H3v-4L16.5 3.5z"></path>
                                </svg>
                            </span>
                            <span>Szerkeszt</span>
                        </a>
                    </div>
                </article>
            @empty
                <article class="car-entry-empty">
                    <h3>Még nincs autó rögzítve</h3>
                    <p>Kezdd egy új jármű létrehozásával, és építsd fel a saját flottád.</p>
                    <a href="{{ route('cars.create') }}" class="btn car-btn-main">Első autó hozzáadása</a>
                </article>
            @endforelse
        </section>
    </section>

@endsection