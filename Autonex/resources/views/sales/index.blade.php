@extends('layouts.app')

@section('content')

@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

<section class="market-shell market-page-enter">
    <header class="market-hero">
        <div>
            <h1 class="page-title">Market</h1>
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
            {{-- Row 1: Vehicle Type, Brand, Model, Body, Fuel, Condition --}}
            <div class="mf-grid mf-grid--5">
                <div class="mf-field">
                    <label class="mf-label">Jármű típus</label>
                    <select id="filter-vehicle-type" class="mf-input">
                        <option value="all">Mindegy</option>
                        @foreach(array_keys($vehicleConfig['types']) as $vt)
                            <option value="{{ mb_strtolower($vt) }}">{{ $vt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Márka</label>
                    <select id="filter-brand" class="mf-input">
                        <option value="all">Mindegy</option>
                    </select>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Modell</label>
                    <select id="filter-model" class="mf-input">
                        <option value="all">Mindegy</option>
                    </select>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Kivitel</label>
                    <select id="filter-body-type" class="mf-input">
                        <option value="all">Mindegy</option>
                        @foreach(collect($vehicleConfig['body_types'])->flatten()->unique()->sort() as $bt)
                            <option value="{{ mb_strtolower($bt) }}">{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Üzemanyag</label>
                    <select id="filter-fuel-type" class="mf-input">
                        <option value="all">Mindegy</option>
                        <option value="benzin">Benzin</option>
                        <option value="dízel">Dízel</option>
                        <option value="hibrid">Hibrid</option>
                        <option value="elektromos">Elektromos</option>
                        <option value="lpg">LPG</option>
                    </select>
                </div>
            </div>

            <div class="mf-grid mf-grid--5" style="margin-top:0;">
                <div class="mf-field">
                    <label class="mf-label">Állapot</label>
                    <select id="filter-condition" class="mf-input">
                        <option value="all">Mindegy</option>
                        <option value="újszerű">Újszerű</option>
                        <option value="megkímélt">Megkímélt</option>
                        <option value="normál">Normál</option>
                        <option value="sérült">Sérült</option>
                    </select>
                </div>
            </div>

            {{-- Row 2: Price range, Mileage range, Engine range, Sort --}}
            <div class="mf-grid mf-grid--4">
                <div class="mf-field">
                    <label class="mf-label">Vételár</label>
                    <div class="mf-range">
                        <input id="filter-price-min" type="number" class="mf-input mf-input--sm" placeholder="-tól" min="0"> 
                        <span class="mf-range-sep">–</span>
                        <input id="filter-price-max" type="number" class="mf-input mf-input--sm" placeholder="-ig" min="0">
                        <span class="mf-range-unit">Ft</span>
                    </div>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Km. óra állás</label>
                    <div class="mf-range">
                        <input id="filter-km-min" type="number" class="mf-input mf-input--sm" placeholder="-tól" min="0">
                        <span class="mf-range-sep">–</span>
                        <input id="filter-km-max" type="number" class="mf-input mf-input--sm" placeholder="-ig" min="0">
                        <span class="mf-range-unit">km</span>
                    </div>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Hengerűrtartalom</label>
                    <div class="mf-range">
                        <input id="filter-cc-min" type="number" class="mf-input mf-input--sm" placeholder="-tól" min="0">
                        <span class="mf-range-sep">–</span>
                        <input id="filter-cc-max" type="number" class="mf-input mf-input--sm" placeholder="-ig" min="0">
                        <span class="mf-range-unit">cm³</span>
                    </div>
                </div>
                <div class="mf-field">
                    <label class="mf-label">Rendezés</label>
                    <select id="market-sort" class="mf-input">
                        <option value="date-desc">Legújabb</option>
                        <option value="price-asc">Ár: növekvő</option>
                        <option value="price-desc">Ár: csökkenő</option>
                    </select>
                </div>
            </div>

            {{-- Row 3: Text search + action --}}
            <div class="mf-grid mf-grid--search">
                <div class="mf-field mf-field--grow">
                    <div class="market-search-wrap">
                        <span class="market-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
                        </span>
                        <input id="market-search" type="text" placeholder="Keresés márka, modell, leírás alapján...">
                    </div>
                </div>
                <button type="button" id="mf-reset" class="mf-btn-reset">Szűrők törlése</button>
            </div>
        </div>
    </div>

    <section class="market-section">
        @if($sales->count() > 0)
            <p style="margin:0 0 14px;opacity:.72;font-size:.92rem;">
                Megjelenítve {{ $sales->firstItem() }}-{{ $sales->lastItem() }} / {{ $sales->total() }} találat.
            </p>
        @endif

        <div class="market-card-grid" id="market-list">
            @forelse($sales as $sale)
                @php
                    $availableImages = $sale->images
                        ->sortBy('sort_order')
                        ->filter(fn ($image) => \Illuminate\Support\Facades\Storage::disk('public')->exists($image->path));
                    $img = $availableImages->first();
                    $imgUrl = $img ? asset('storage/' . $img->path) : null;
                    $imgCount = $availableImages->count();
                @endphp
                <a href="{{ route('sales.show', $sale) }}" class="market-card-item" data-market-item
                    data-brand="{{ mb_strtolower($sale->brand ?? '') }}"
                    data-model="{{ mb_strtolower($sale->model ?? '') }}"
                    data-vehicle-type="{{ mb_strtolower($sale->vehicle_type ?? '') }}"
                    data-body-type="{{ mb_strtolower($sale->body_type ?? '') }}"
                    data-fuel-type="{{ mb_strtolower($sale->fuel_type ?? '') }}"
                    data-condition="{{ mb_strtolower($sale->car_condition ?? '') }}"
                    data-price="{{ (float) $sale->price }}"
                    data-mileage="{{ (int) $sale->mileage }}"
                    data-engine="{{ (int) $sale->engine_cc }}"
                    data-date="{{ $sale->created_at?->timestamp ?? 0 }}"
                    data-search="{{ mb_strtolower(($sale->brand ?? '') . ' ' . ($sale->model ?? '') . ' ' . ($sale->vehicle_type ?? '') . ' ' . ($sale->description ?? '') . ' ' . ($sale->car_condition ?? '') . ' ' . ($sale->car?->make_model ?? '')) }}">

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
                        <h3 class="market-card-title">{{ trim(($sale->brand ?? '') . ' ' . ($sale->model ?? $sale->car?->make_model ?? 'Ismeretlen')) }}</h3>

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
    var vehicleConfig = @json($vehicleConfig['types']);
    var bodyConfig = @json($vehicleConfig['body_types']);

    var toggleBtn = document.getElementById('marketSearchToggle');
    var panel = document.getElementById('marketSearchPanel');
    var searchInput = document.getElementById('market-search');
    var sortSelect = document.getElementById('market-sort');
    var list = document.getElementById('market-list');
    var cards = Array.from(document.querySelectorAll('[data-market-item]'));

    var filterVehicle = document.getElementById('filter-vehicle-type');
    var filterBrand = document.getElementById('filter-brand');
    var filterModel = document.getElementById('filter-model');
    var filterBody = document.getElementById('filter-body-type');
    var filterFuel = document.getElementById('filter-fuel-type');
    var filterCondition = document.getElementById('filter-condition');

    var priceMin = document.getElementById('filter-price-min');
    var priceMax = document.getElementById('filter-price-max');
    var kmMin = document.getElementById('filter-km-min');
    var kmMax = document.getElementById('filter-km-max');
    var ccMin = document.getElementById('filter-cc-min');
    var ccMax = document.getElementById('filter-cc-max');
    var resetBtn = document.getElementById('mf-reset');

    function populateSelect(sel, items, placeholder) {
        var prev = sel.value;
        sel.innerHTML = '<option value="all">' + placeholder + '</option>';
        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item.toLowerCase();
            opt.textContent = item;
            sel.appendChild(opt);
        });
        if (prev !== 'all' && sel.querySelector('option[value="' + prev + '"]')) {
            sel.value = prev;
        } else {
            sel.value = 'all';
        }
    }

    function updateBrands() {
        var vt = filterVehicle.value;
        var brands = [];
        if (vt !== 'all') {
            var typeBrands = vehicleConfig[Object.keys(vehicleConfig).find(function(k) { return k.toLowerCase() === vt; }) || ''];
            if (typeBrands) brands = Object.keys(typeBrands).sort();
        } else {
            var seen = {};
            Object.keys(vehicleConfig).forEach(function(type) {
                Object.keys(vehicleConfig[type]).forEach(function(b) { if (!seen[b]) { seen[b] = true; brands.push(b); } });
            });
            brands.sort();
        }
        populateSelect(filterBrand, brands, 'Mindegy');
        updateModels();
        updateBodyTypes();
    }

    function updateModels() {
        var vt = filterVehicle.value;
        var br = filterBrand.value;
        var models = [];
        if (br !== 'all') {
            Object.keys(vehicleConfig).forEach(function(type) {
                if (vt !== 'all' && type.toLowerCase() !== vt) return;
                Object.keys(vehicleConfig[type]).forEach(function(b) {
                    if (b.toLowerCase() === br) models = models.concat(vehicleConfig[type][b]);
                });
            });
            models = models.filter(function(m, i, a) { return a.indexOf(m) === i; }).sort();
        }
        populateSelect(filterModel, models, 'Mindegy');
    }

    function updateBodyTypes() {
        var vt = filterVehicle.value;
        var bodies = [];
        if (vt !== 'all') {
            var key = Object.keys(bodyConfig).find(function(k) { return k.toLowerCase() === vt; });
            if (key) bodies = bodyConfig[key];
        } else {
            var seen = {};
            Object.keys(bodyConfig).forEach(function(type) {
                bodyConfig[type].forEach(function(b) { if (!seen[b]) { seen[b] = true; bodies.push(b); } });
            });
            bodies.sort();
        }
        populateSelect(filterBody, bodies, 'Mindegy');
    }

    filterVehicle.addEventListener('change', function() { updateBrands(); applyFilters(); });
    filterBrand.addEventListener('change', function() { updateModels(); applyFilters(); });

    // Initialize cascading dropdowns
    updateBrands();

    toggleBtn.addEventListener('click', function() {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    function numVal(input) {
        var v = parseFloat(input.value);
        return isNaN(v) ? null : v;
    }

    function inRange(val, minInput, maxInput) {
        var lo = numVal(minInput), hi = numVal(maxInput);
        if (lo !== null && val < lo) return false;
        if (hi !== null && val > hi) return false;
        return true;
    }

    function applyFilters() {
        var term = (searchInput.value || '').trim().toLowerCase();
        var br = filterBrand.value;
        var mdl = filterModel.value;
        var vt = filterVehicle.value;
        var bt = filterBody.value;
        var ft = filterFuel.value;
        var cond = filterCondition.value;

        cards.forEach(function(c) {
            var ok = true;
            if (term && (c.dataset.search || '').indexOf(term) === -1) ok = false;
            if (br !== 'all' && c.dataset.brand !== br) ok = false;
            if (mdl !== 'all' && c.dataset.model !== mdl) ok = false;
            if (vt !== 'all' && c.dataset.vehicleType !== vt) ok = false;
            if (bt !== 'all' && c.dataset.bodyType !== bt) ok = false;
            if (ft !== 'all' && c.dataset.fuelType !== ft) ok = false;
            if (cond !== 'all' && c.dataset.condition !== cond) ok = false;
            if (!inRange(Number(c.dataset.price || 0), priceMin, priceMax)) ok = false;
            if (!inRange(Number(c.dataset.mileage || 0), kmMin, kmMax)) ok = false;
            if (!inRange(Number(c.dataset.engine || 0), ccMin, ccMax)) ok = false;
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

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        [filterVehicle, filterBrand, filterModel, filterBody, filterFuel, filterCondition].forEach(function(s) { s.value = 'all'; });
        [priceMin, priceMax, kmMin, kmMax, ccMin, ccMax].forEach(function(i) { i.value = ''; });
        sortSelect.value = 'date-desc';
        updateBrands();
        applySort();
        applyFilters();
    });

    searchInput.addEventListener('input', applyFilters);
    [filterModel, filterBody, filterFuel, filterCondition].forEach(function(el) {
        el.addEventListener('change', applyFilters);
    });
    [priceMin, priceMax, kmMin, kmMax, ccMin, ccMax].forEach(function(el) {
        el.addEventListener('input', applyFilters);
    });
    sortSelect.addEventListener('change', function() { applySort(); applyFilters(); });
})();
</script>

@endsection
