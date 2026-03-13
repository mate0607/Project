@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cars-ui.css') }}">
@endpush

@section('content')

    <section class="cars-shell cars-page-enter">
        <header class="cars-topbar">
            <div>
                <h1 class="page-title">Saját autóim</h1>
                <p class="page-subtitle">Járműveid, szerviz előzményeik és aktuális állapotuk egy helyen.</p>
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

        {{-- Keresés és szűrés --}}
        <div class="cars-filter-bar">
            <input type="text" id="carSearch" class="cars-search-input" placeholder="Keresés típus szerint...">
            <select id="carYearFilter" class="cars-year-select">
                <option value="">Összes évjárat</option>
                @foreach($cars->whereNotNull('year')->pluck('year')->unique()->sortDesc() as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <section class="cars-card-grid" id="carsGrid" aria-label="Saját autók listája">
            @forelse($cars as $car)
                @php
                    $activeService = $car->appointments->whereIn('status', ['in_progress', 'confirmed'])->whereNotNull('service_stage')->first();
                @endphp
                <a href="{{ route('cars.show', $car) }}" class="car-entry-card car-entry-link" data-make="{{ strtolower($car->make_model) }}" data-year="{{ $car->year }}">
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

                    @if($activeService)
                        <div class="car-entry-service-status">
                            <span class="car-service-indicator"></span>
                            Szervizben
                        </div>
                    @endif
                </a>
            @empty
                <article class="car-entry-empty">
                    <h3>Még nincs autó rögzítve</h3>
                    <p>Kezdd egy új jármű létrehozásával.</p>
                    <a href="{{ route('cars.create') }}" class="btn car-btn-main">Első autó hozzáadása</a>
                </article>
            @endforelse
        </section>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('carSearch');
            const yearFilter = document.getElementById('carYearFilter');
            const cards = document.querySelectorAll('.car-entry-link');

            function filterCars() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const yearVal = yearFilter.value;

                cards.forEach(function (card) {
                    const make = card.getAttribute('data-make') || '';
                    const year = card.getAttribute('data-year') || '';
                    const matchSearch = !searchVal || make.indexOf(searchVal) !== -1;
                    const matchYear = !yearVal || year === yearVal;
                    card.style.display = (matchSearch && matchYear) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterCars);
            yearFilter.addEventListener('change', filterCars);
        });
    </script>

@endsection