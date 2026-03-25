@extends('layouts.app')

@section('content')

@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

<section class="market-shell market-page-enter">
    <header class="market-hero">
        <div>
            <h1 class="page-title">Market</h1>
            <p class="page-subtitle">Fedezd fel az autós ajánlatokat egy modern felületen.</p>
        </div>
        <div class="market-hero-actions" style="display:flex;gap:8px;align-items:center;">
            <button type="button" id="marketSearchToggle" title="Keresés" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,0.18);border:1px solid rgba(96,165,250,0.35);cursor:pointer;transition:background 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>
            @if($isAdmin)
                <a href="{{ route('sales.create') }}" class="btn sale-btn-main">+ Új ajánlat</a>
            @endif
        </div>
    </header>

    <div id="marketSearchPanel" style="display:none;margin-bottom:12px;">
        <div class="market-toolbar">
            <div class="market-search-wrap">
                <span class="market-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
                </span>
                <input id="market-search" type="text" placeholder="Keresés modell, leírás alapján...">
            </div>

            <div class="market-filter-row" style="flex-wrap:wrap;">
                <select id="filter-vehicle-type" class="market-select">
                    <option value="all">Jármű típus: mind</option>
                    @foreach($allSalesForFilters->pluck('vehicle_type')->filter()->unique() as $vt)
                        <option value="{{ mb_strtolower($vt) }}">{{ $vt }}</option>
                    @endforeach
                </select>
                <select id="filter-body-type" class="market-select">
                    <option value="all">Karosszéria: mind</option>
                    @foreach($allSalesForFilters->pluck('body_type')->filter()->unique() as $bt)
                        <option value="{{ mb_strtolower($bt) }}">{{ $bt }}</option>
                    @endforeach
                </select>
                <select id="filter-fuel-type" class="market-select">
                    <option value="all">Üzemanyag: mind</option>
                    @foreach($allSalesForFilters->pluck('fuel_type')->filter()->unique() as $ft)
                        <option value="{{ mb_strtolower($ft) }}">{{ $ft }}</option>
                    @endforeach
                </select>
                <select id="filter-condition" class="market-select">
                    <option value="all">Állapot: mind</option>
                    @foreach($allSalesForFilters->pluck('car_condition')->filter()->unique() as $cond)
                        <option value="{{ mb_strtolower($cond) }}">{{ $cond }}</option>
                    @endforeach
                </select>
                <select id="filter-price-range" class="market-select">
                    <option value="all">Ár: mind</option>
                    <option value="0-2000000">0 – 2 000 000 Ft</option>
                    <option value="2000001-5000000">2 000 001 – 5 000 000 Ft</option>
                    <option value="5000001-max">5 000 001+ Ft</option>
                </select>
            </div>

            <div style="display:flex;align-items:center;gap:6px;">
                <label style="color:var(--text-muted);font-size:13px;">Rendezés:</label>
                <select id="market-sort" class="market-select" style="min-width:140px;">
                    <option value="date-desc">Legújabb</option>
                    <option value="price-asc">Ár: növekvő</option>
                    <option value="price-desc">Ár: csökkenő</option>
                </select>
            </div>
        </div>
    </div>

    <section class="market-section">
        <div class="market-card-grid" id="market-list">
            @forelse($sales as $sale)
                @php
                    $img = $sale->images->sortBy('sort_order')->first();
                    $imgUrl = $img ? asset('storage/' . $img->path) : null;
                    $imgCount = $sale->images->count();
                @endphp
                <a href="{{ route('sales.show', $sale) }}" class="market-card-item" data-market-item
                    data-vehicle-type="{{ mb_strtolower($sale->vehicle_type ?? '') }}"
                    data-body-type="{{ mb_strtolower($sale->body_type ?? '') }}"
                    data-fuel-type="{{ mb_strtolower($sale->fuel_type ?? '') }}"
                    data-condition="{{ mb_strtolower($sale->car_condition ?? '') }}"
                    data-price="{{ (float) $sale->price }}"
                    data-date="{{ $sale->created_at?->timestamp ?? 0 }}"
                    data-search="{{ mb_strtolower(($sale->model ?? '') . ' ' . ($sale->vehicle_type ?? '') . ' ' . ($sale->description ?? '') . ' ' . ($sale->car_condition ?? '') . ' ' . ($sale->car?->make_model ?? '')) }}">

                    <div class="market-card-img">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $sale->model ?? $sale->car?->make_model ?? 'Eladó jármű' }}" loading="lazy">
                        @else
                            <div class="market-card-noimg">Nincs kép</div>
                        @endif
                        @if($imgCount > 1)
                            <span class="market-card-imgcount">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                {{ $imgCount }}
                            </span>
                        @endif
                    </div>

                    <div class="market-card-body">
                        <h3 class="market-card-title">{{ $sale->model ?? $sale->car?->make_model ?? 'Ismeretlen' }}</h3>

                        <div class="market-card-tags">
                            @if($sale->vehicle_type)
                                <span class="market-tag-chip">{{ $sale->vehicle_type }}</span>
                            @endif
                            @if($sale->body_type)
                                <span class="market-tag-chip">{{ $sale->body_type }}</span>
                            @endif
                            @if($sale->fuel_type)
                                <span class="market-tag-chip">{{ $sale->fuel_type }}</span>
                            @endif
                            @if($sale->car_condition)
                                <span class="market-tag-chip">{{ $sale->car_condition }}</span>
                            @endif
                        </div>

                        <div class="market-card-specs">
                            @if($sale->fuel_type)<span>{{ $sale->fuel_type }}</span>@endif
                            @if($sale->engine_cc)<span>{{ number_format($sale->engine_cc, 0, ',', ' ') }} cm³</span>@endif
                            @if($sale->mileage)<span>{{ number_format($sale->mileage, 0, ',', ' ') }} km</span>@endif
                        </div>

                        <p class="market-card-desc">{{ \Illuminate\Support\Str::limit($sale->description ?: 'Nincs leírás.', 120) }}</p>
                    </div>

                    <div class="market-card-pricecol">
                        <strong class="market-card-price">{{ number_format((float)$sale->price, 0, ',', ' ') }} Ft</strong>
                    </div>
                </a>
            @empty
                <article class="market-empty" style="grid-column: 1 / -1;">
                    <h3>Még nincs ajánlat</h3>
                    <p>Hozz létre egy új listinget.</p>
                    @if($isAdmin)
                        <a href="{{ route('sales.create') }}" class="btn sale-btn-main">Első ajánlat létrehozása</a>
                    @endif
                </article>
            @endforelse
        </div>

        @if($sales->hasPages())
            <div class="market-pagination">
                @if($sales->onFirstPage())
                    <span class="market-page-arrow disabled">&laquo; Előző</span>
                @else
                    <a href="{{ $sales->previousPageUrl() }}" class="market-page-arrow">&laquo; Előző</a>
                @endif

                @foreach($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
                    @if($page == $sales->currentPage())
                        <span class="market-page-num active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="market-page-num">{{ $page }}</a>
                    @endif
                @endforeach

                @if($sales->hasMorePages())
                    <a href="{{ $sales->nextPageUrl() }}" class="market-page-arrow">Következő &raquo;</a>
                @else
                    <span class="market-page-arrow disabled">Következő &raquo;</span>
                @endif
            </div>
        @endif
    </section>
</section>

<script>
(function () {
    var toggleBtn = document.getElementById('marketSearchToggle');
    var panel = document.getElementById('marketSearchPanel');
    var searchInput = document.getElementById('market-search');
    var sortSelect = document.getElementById('market-sort');
    var list = document.getElementById('market-list');
    var cards = Array.from(document.querySelectorAll('[data-market-item]'));

    var filterVehicle = document.getElementById('filter-vehicle-type');
    var filterBody = document.getElementById('filter-body-type');
    var filterFuel = document.getElementById('filter-fuel-type');
    var filterCondition = document.getElementById('filter-condition');
    var filterPrice = document.getElementById('filter-price-range');

    toggleBtn.addEventListener('click', function() {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    function matchPrice(p, range) {
        if (range === 'all') return true;
        if (range === '5000001-max') return p >= 5000001;
        var parts = range.split('-');
        return p >= parseInt(parts[0]) && p <= parseInt(parts[1]);
    }

    function applyFilters() {
        var term = (searchInput.value || '').trim().toLowerCase();
        var vt = filterVehicle.value;
        var bt = filterBody.value;
        var ft = filterFuel.value;
        var cond = filterCondition.value;
        var price = filterPrice.value;

        cards.forEach(function(c) {
            var ok = true;
            if (term && (c.dataset.search || '').indexOf(term) === -1) ok = false;
            if (vt !== 'all' && c.dataset.vehicleType !== vt) ok = false;
            if (bt !== 'all' && c.dataset.bodyType !== bt) ok = false;
            if (ft !== 'all' && c.dataset.fuelType !== ft) ok = false;
            if (cond !== 'all' && c.dataset.condition !== cond) ok = false;
            if (!matchPrice(Number(c.dataset.price || 0), price)) ok = false;
            c.style.display = ok ? '' : 'none';
        });
    }

    function applySort() {
        var val = sortSelect.value;
        var sorted = cards.slice().sort(function(a, b) {
            if (val === 'price-asc') return Number(a.dataset.price) - Number(b.dataset.price);
            if (val === 'price-desc') return Number(b.dataset.price) - Number(a.dataset.price);
            return Number(b.dataset.date) - Number(a.dataset.date);
        });
        sorted.forEach(function(c) { list.appendChild(c); });
    }

    searchInput.addEventListener('input', applyFilters);
    [filterVehicle, filterBody, filterFuel, filterCondition, filterPrice].forEach(function(el) {
        el.addEventListener('change', applyFilters);
    });
    sortSelect.addEventListener('change', function() { applySort(); applyFilters(); });
})();
</script>

@endsection
