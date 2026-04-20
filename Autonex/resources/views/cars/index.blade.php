@extends('layouts.app')


@section('content')

    <section class="cars-shell cars-page-enter">
        <header class="cars-topbar">
            <div>
                <h1 class="page-title">Saját autóim</h1>
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

        {{-- Keresés --}}
        <div class="cars-filter-bar">
            <input type="text" id="carSearch" class="cars-search-input" placeholder="Keresés típus, rendszám vagy évjárat szerint...">
        </div>

        <section class="cars-card-grid" id="carsGrid" aria-label="Saját autók listája">
            @forelse($cars as $car)
                @php
                    $activeService = $car->appointments->whereIn('status', ['in_progress', 'confirmed'])->whereNotNull('service_stage')->first();
                @endphp
                <a href="{{ route('cars.show', $car) }}" class="car-entry-card car-entry-link"
                   data-make="{{ strtolower($car->make_model) }}"
                   data-year="{{ $car->year }}"
                   data-plate="{{ strtolower($car->license_plate ?? '') }}">
                    <div class="car-entry-head">
                        <span class="car-entry-chip">#{{ $car->id }}</span>
                        @if($car->unread_messages_count > 0)
                            <span class="car-msg-badge" title="Olvasatlan üzenetek">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                {{ $car->unread_messages_count }}
                            </span>
                        @endif
                    </div>

                    <div class="car-entry-meta">
                        <div>
                            <span>Típus</span>
                            <strong>{{ $car->make_model }}</strong>
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
            var cards = document.querySelectorAll('.car-entry-link');

            function filterCars() {
                var searchVal = searchInput.value.toLowerCase().trim();

                cards.forEach(function (card) {
                    var make = card.getAttribute('data-make') || '';
                    var year = card.getAttribute('data-year') || '';
                    var plate = card.getAttribute('data-plate') || '';

                    var ok = !searchVal ||
                        make.indexOf(searchVal) !== -1 ||
                        year.indexOf(searchVal) !== -1 ||
                        plate.indexOf(searchVal) !== -1;

                    card.style.display = ok ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterCars);
        });
    </script>

@endsection