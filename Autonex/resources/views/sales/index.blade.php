@extends('layouts.app')


@section('content')

@php
    // A nezetben tobb szekcio is ugyanarra az adathalmazra epul, ezert itt keszitjuk elo.
    $salesCollection = collect($sales);
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';

    $allSales = $salesCollection->sortByDesc('created_at');

    // A kliensoldali kereseshez egyseges, kisbetus szovegmezot epitunk.
    $searchableText = fn ($sale) => mb_strtolower(
        ($sale->car?->make_model ?? '') . ' ' . ($sale->description ?? '') . ' ' . ($sale->car_condition ?? '')
    );
@endphp

<section class="market-shell market-page-enter">
    <header class="market-hero">
        <div>
            <h1 class="page-title">Market</h1>
            <p class="page-subtitle">Fedezd fel az autós ajánlatokat, szolgáltatásokat és hirdetéseket egy modern böngésző felületen.</p>
        </div>
        <div class="market-hero-actions">
            @if($isAdmin)
                <a href="{{ route('sales.create') }}" class="btn market-btn-main">+ Új ajánlat</a>
            @endif
        </div>
    </header>

    <section class="market-toolbar" aria-label="Keresés és szűrés">
        <div class="market-search-wrap">
            <span class="market-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
            </span>
            <input id="market-search" type="text" placeholder="Keresés cím, leírás vagy autó alapján...">
        </div>

        <div class="market-filter-row">
            <button class="market-filter-chip active" type="button" data-filter-status="all">Minden</button>
            <button class="market-filter-chip" type="button" data-filter-status="active">Aktív</button>
            <button class="market-filter-chip" type="button" data-filter-status="inactive">Inaktív</button>

            <select id="market-condition-filter" class="market-select" aria-label="Állapot szerint">
                <option value="all">Állapot: mind</option>
                @foreach($allSales->pluck('car_condition')->filter()->unique()->values() as $condition)
                    <option value="{{ mb_strtolower($condition) }}">{{ $condition }}</option>
                @endforeach
            </select>

            <select id="market-price-filter" class="market-select" aria-label="Ár szerint">
                <option value="all">Ár: mind</option>
                <option value="0-2000000">0 - 2 000 000 Ft</option>
                <option value="2000001-5000000">2 000 001 - 5 000 000 Ft</option>
                <option value="5000001-max">5 000 001+ Ft</option>
            </select>
        </div>
    </section>

    <section class="market-section">
        <div class="market-section-head">
            <h2>Összes listing</h2>
            <p>Teljes kínálat a választott szűrők alapján.</p>
        </div>

        <div class="market-grid market-grid-all" id="market-all-grid">
            @forelse($allSales as $sale)
                <article class="market-card" data-market-item data-status="{{ $sale->is_active ? 'active' : 'inactive' }}" data-condition="{{ mb_strtolower((string) ($sale->car_condition ?? '')) }}" data-price="{{ (float) $sale->price }}" data-search="{{ $searchableText($sale) }}" onclick="if(!event.target.closest('.carousel-btn, .market-btn-soft, .inline-form, button'))window.location='{{ route('sales.show', $sale) }}'" style="cursor:pointer;">
                    @if($sale->images->count())
                        <div class="market-card-media" style="position:relative;">
                            <div class="sale-carousel" id="carousel-{{ $sale->id }}">
                                @foreach($sale->images as $img)
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $sale->car?->make_model }}" class="sale-carousel-img" style="width:100%;height:200px;object-fit:cover;display:{{ $loop->first ? 'block' : 'none' }};">
                                @endforeach
                            </div>
                            @if($sale->images->count() > 1)
                                <button type="button" class="carousel-btn carousel-prev" onclick="event.preventDefault();slideCarousel('carousel-{{ $sale->id }}',-1)">&#10094;</button>
                                <button type="button" class="carousel-btn carousel-next" onclick="event.preventDefault();slideCarousel('carousel-{{ $sale->id }}',1)">&#10095;</button>
                            @endif
                        </div>
                    @elseif($sale->image)
                        <div class="market-card-media"><img src="{{ asset('storage/' . $sale->image) }}" alt="{{ $sale->car?->make_model }}" style="width:100%;height:200px;object-fit:cover;"></div>
                    @endif
                    <div class="market-card-body">
                        <h3>{{ $sale->car?->make_model ?? 'Ismeretlen autó' }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($sale->description ?: 'Nincs leírás megadva.', 105) }}</p>

                        <div class="market-card-tags">
                            <span class="market-tag">{{ $sale->car_condition ?? 'Állapot: n/a' }}</span>
                            <span class="market-tag {{ $sale->is_active ? 'is-active' : 'is-inactive' }}">{{ $sale->is_active ? 'Aktív' : 'Inaktív' }}</span>
                        </div>
                    </div>

                    <div class="market-card-foot">
                        <strong>{{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</strong>
                        <div class="market-card-actions">
                            @if($isAdmin)
                                <a href="{{ route('sales.edit', $sale) }}" class="market-btn-soft">Szerkeszt</a>

                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="market-btn-soft market-btn-danger">Törlés</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <article class="market-empty">
                    <h3>Még nincs ajánlat</h3>
                    <p>Hozz létre egy új listinget, hogy megjelenjen a piactéren.</p>
                    @if($isAdmin)
                        <a href="{{ route('sales.create') }}" class="btn market-btn-main">Első ajánlat létrehozása</a>
                    @endif
                </article>
            @endforelse
        </div>
    </section>
</section>



<script>
function slideCarousel(id, dir) {
    var wrap = document.getElementById(id);
    if (!wrap) return;
    var imgs = wrap.querySelectorAll('.sale-carousel-img');
    var idx = 0;
    imgs.forEach(function(img, i) { if (img.style.display !== 'none') idx = i; });
    imgs[idx].style.display = 'none';
    idx = (idx + dir + imgs.length) % imgs.length;
    imgs[idx].style.display = 'block';
    var counter = document.getElementById(id + '-counter');
    if (counter) counter.textContent = (idx + 1) + ' / ' + imgs.length;
}
</script>

<script>
    (function () {
        // Kliensoldali filter allapotok: statusz, allapot es ar-sav szerint.
        const searchInput = document.getElementById('market-search');
        const statusButtons = Array.from(document.querySelectorAll('[data-filter-status]'));
        const conditionSelect = document.getElementById('market-condition-filter');
        const priceSelect = document.getElementById('market-price-filter');
        const cards = Array.from(document.querySelectorAll('[data-market-item]'));

        let statusFilter = 'all';
        const PRICE_RANGES = {
            '0-2000000': [0, 2000000],
            '2000001-5000000': [2000001, 5000000],
        };

        function matchPrice(cardPrice, range) {
            if (range === 'all') {
                return true;
            }

            if (range === '5000001-max') {
                return cardPrice >= 5000001;
            }

            const selectedRange = PRICE_RANGES[range];

            if (selectedRange) {
                const [min, max] = selectedRange;

                return cardPrice >= min && cardPrice <= max;
            }

            return true;
        }

        // Egy kartyara vonatkozo osszes feltetel egyszerre ellenorzesre kerul.
        function cardMatchesFilters(card, term, condition, price) {
            const cardSearch = card.dataset.search || '';
            const cardStatus = card.dataset.status || 'inactive';
            const cardCondition = card.dataset.condition || '';
            const cardPrice = Number(card.dataset.price || 0);

            const matchTerm = term === '' || cardSearch.includes(term);
            const matchStatus = statusFilter === 'all' || cardStatus === statusFilter;
            const matchCondition = condition === 'all' || cardCondition === condition;
            const matchPriceRange = matchPrice(cardPrice, price);

            return matchTerm && matchStatus && matchCondition && matchPriceRange;
        }

        function applyFilters() {
            const term = (searchInput?.value || '').trim().toLowerCase();
            const condition = conditionSelect?.value || 'all';
            const price = priceSelect?.value || 'all';

            cards.forEach((card) => {
                card.style.display = cardMatchesFilters(card, term, condition, price) ? '' : 'none';
            });
        }

        statusButtons.forEach((button) => {
            button.addEventListener('click', () => {
                statusFilter = button.dataset.filterStatus || 'all';
                statusButtons.forEach((b) => b.classList.remove('active'));
                button.classList.add('active');
                applyFilters();
            });
        });

        searchInput?.addEventListener('input', applyFilters);
        conditionSelect?.addEventListener('change', applyFilters);
        priceSelect?.addEventListener('change', applyFilters);
    })();
</script>

@endsection
