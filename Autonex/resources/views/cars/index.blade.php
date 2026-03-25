@extends('layouts.app')


@section('content')

    <section class="cars-shell cars-page-enter">
        <header class="cars-topbar">
            <div>
                <h1 class="page-title">Saját autóim</h1>
                <p class="page-subtitle">Járműveid, szerviz előzményeik és aktuális állapotuk egy helyen.</p>
            </div>
            <a href="{{ route('cars.create') }}" class="btn car-btn-main car-btn-themed">
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
            <button type="button" id="advancedToggle" class="btn car-btn-filter-toggle">Advanced</button>
        </div>

        <div class="cars-advanced-panel" id="advancedPanel" style="display:none;">
            <div class="cars-adv-row">
                <div class="cars-adv-field">
                    <label for="filterYear">Évjárat</label>
                    <select id="filterYear" class="cars-adv-input">
                        <option value="">Mind</option>
                        @foreach($cars->whereNotNull('year')->pluck('year')->unique()->sortDesc() as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cars-adv-field">
                    <label for="filterModel">Modell</label>
                    <input type="text" id="filterModel" class="cars-adv-input" placeholder="pl. Corolla">
                </div>
                <div class="cars-adv-field">
                    <label for="filterPlate">Rendszám</label>
                    <input type="text" id="filterPlate" class="cars-adv-input" placeholder="pl. ABC-123">
                </div>
                <div class="cars-adv-field">
                    <label for="filterVin">VIN</label>
                    <input type="text" id="filterVin" class="cars-adv-input" placeholder="pl. 1HGCM82...">
                </div>
            </div>
        </div>

        <section class="cars-card-grid" id="carsGrid" aria-label="Saját autók listája">
            @forelse($cars as $car)
                @php
                    $activeService = $car->appointments->whereIn('status', ['in_progress', 'confirmed'])->whereNotNull('service_stage')->first();
                @endphp
                <a href="{{ route('cars.show', $car) }}" class="car-entry-card car-entry-link"
                   data-make="{{ strtolower($car->make_model) }}"
                   data-year="{{ $car->year }}"
                   data-plate="{{ strtolower($car->license_plate ?? '') }}"
                   data-vin="{{ strtolower($car->vin ?? '') }}">
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
                            <span>Rendszám</span>
                            <strong>{{ $car->license_plate ?? 'Nincs megadva' }}</strong>
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
                    <a href="{{ route('cars.create') }}" class="btn car-btn-main car-btn-themed">Első autó hozzáadása</a>
                </article>
            @endforelse
        </section>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('carSearch');
            var advToggle = document.getElementById('advancedToggle');
            var advPanel = document.getElementById('advancedPanel');
            var filterYear = document.getElementById('filterYear');
            var filterModel = document.getElementById('filterModel');
            var filterPlate = document.getElementById('filterPlate');
            var filterVin = document.getElementById('filterVin');
            var cards = document.querySelectorAll('.car-entry-link');

            advToggle.addEventListener('click', function () {
                var open = advPanel.style.display !== 'none';
                advPanel.style.display = open ? 'none' : 'flex';
                advToggle.classList.toggle('active', !open);
            });

            function filterCars() {
                var searchVal = searchInput.value.toLowerCase().trim();
                var yearVal = filterYear.value;
                var modelVal = filterModel.value.toLowerCase().trim();
                var plateVal = filterPlate.value.toLowerCase().trim();
                var vinVal = filterVin.value.toLowerCase().trim();

                cards.forEach(function (card) {
                    var make = card.getAttribute('data-make') || '';
                    var year = card.getAttribute('data-year') || '';
                    var plate = card.getAttribute('data-plate') || '';
                    var vin = card.getAttribute('data-vin') || '';

                    var ok = true;
                    if (searchVal && make.indexOf(searchVal) === -1) ok = false;
                    if (yearVal && year !== yearVal) ok = false;
                    if (modelVal && make.indexOf(modelVal) === -1) ok = false;
                    if (plateVal && plate.indexOf(plateVal) === -1) ok = false;
                    if (vinVal && vin.indexOf(vinVal) === -1) ok = false;

                    card.style.display = ok ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterCars);
            filterYear.addEventListener('change', filterCars);
            filterModel.addEventListener('input', filterCars);
            filterPlate.addEventListener('input', filterCars);
            filterVin.addEventListener('input', filterCars);
        });
    </script>

@endsection