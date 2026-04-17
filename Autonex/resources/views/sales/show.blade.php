@extends('layouts.app')

@section('content')

<section class="sales-hero sales-hero-tight">
    <div>
        <p class="sales-kicker">{{ $sale->vehicle_type }}</p>
        <h1 class="page-title">{{ $sale->brand }} {{ $sale->model }}</h1>
    </div>
    <div class="form-actions" style="margin-top: 0; display:flex; gap:8px; align-items:center;">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('sales.edit', $sale) }}" class="market-action-icon" title="Szerkesztés">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd?');" style="display:inline-flex;">
                @csrf
                @method('DELETE')
                <button type="submit" class="market-action-icon market-action-danger" title="Törlés">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </form>
        @endif
        <a href="{{ route('sales.index') }}" class="market-action-icon" title="Vissza">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </a>
    </div>
</section>

@if($sale->images->count())
    <div class="sale-gallery" style="margin-bottom:16px;">
        {{-- Thumbnail sidebar --}}
        <div class="sale-gallery-thumbs" id="gallery-thumbs">
            @foreach($sale->images as $img)
                <div class="sale-gallery-thumb {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}" onclick="galleryGoTo({{ $loop->index }})">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="Kép {{ $loop->iteration }}">
                </div>
            @endforeach
        </div>

        {{-- Main image --}}
        <div class="sale-gallery-main" id="gallery-main">
            @foreach($sale->images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $sale->brand }} {{ $sale->model }}" class="sale-gallery-img" data-index="{{ $loop->index }}" style="display:{{ $loop->first ? 'block' : 'none' }};">
            @endforeach

            @if($sale->images->count() > 1)
                <button type="button" class="gallery-nav gallery-prev" onclick="gallerySlide(-1)">&#10094;</button>
                <button type="button" class="gallery-nav gallery-next" onclick="gallerySlide(1)">&#10095;</button>
                <span class="gallery-counter" id="gallery-counter">1 / {{ $sale->images->count() }}</span>
            @endif
        </div>
    </div>

    {{-- Lightbox --}}
    <div class="gallery-lightbox" id="gallery-lightbox">
        <button type="button" class="gallery-lightbox-close" id="lightbox-close">&times;</button>
        <img id="lightbox-img" src="" alt="Nagyított kép">
    </div>
@elseif($sale->image)
    <div class="card" style="margin-bottom:16px;padding:0;overflow:hidden;">
        <img src="{{ asset('storage/' . $sale->image) }}" alt="{{ $sale->brand }} {{ $sale->model }}" style="width:100%;max-height:520px;object-fit:contain;display:block;background:#0b1220;">
    </div>
@endif

<section class="sales-detail-layout">
    <div class="card sales-detail-main">
        <h3>Fő adatok</h3>
        <div class="sales-detail-grid">
            <div class="sales-detail-item">
                <small>Típus</small>
                <strong>{{ $sale->vehicle_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Márka</small>
                <strong>{{ $sale->brand ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Modell</small>
                <strong>{{ $sale->model ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Karosszéria</small>
                <strong>{{ $sale->body_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Üzemanyag</small>
                <strong>{{ $sale->fuel_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Köbcenti</small>
                <strong>{{ $sale->engine_cc ? $sale->engine_cc . ' cm³' : '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Ár</small>
                <strong>{{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</strong>
            </div>
        </div>
    </div>

    <aside class="card sales-detail-side">
        <h3>Állapot</h3>
        <p><span class="sale-chip sale-chip-soft">{{ $sale->car_condition ?? 'n/a' }}</span></p>
        <p><strong>Kilométer:</strong> {{ $sale->mileage ?? 'n/a' }}</p>
        <p>
            <strong>Státusz:</strong>
            <span class="sale-chip {{ $sale->is_active ? 'sale-chip-active' : 'sale-chip-inactive' }}">
                {{ $sale->is_active ? 'AKTÍV' : 'INAKTÍV' }}
            </span>
        </p>
    </aside>
</section>

<div class="card" style="margin-top: 16px;">
    <h3 style="margin-bottom: 10px;">Leírás</h3>
    <p>{{ $sale->description ?: 'Nincs részletes leírás.' }}</p>
</div>

@auth
    @if($sale->seller_id !== auth()->id())
        <div class="card" style="margin-top: 16px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h3 style="margin:0;">Érdeklődsz?</h3>
                <p style="opacity:.6;margin:4px 0 0;">Írj az eladónak közvetlenül.</p>
            </div>
            <a href="{{ route('messages.show_conversation', $sale) }}" class="btn issue-btn-main">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;vertical-align:middle;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Üzenet küldése
            </a>
        </div>
    @endif
@endauth



<script>
(function() {
    var current = 0;
    var imgs = document.querySelectorAll('#gallery-main .sale-gallery-img');
    var thumbs = document.querySelectorAll('#gallery-thumbs .sale-gallery-thumb');
    var counter = document.getElementById('gallery-counter');
    var total = imgs.length;

    // Lightbox elements
    var lightbox = document.getElementById('gallery-lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var lightboxClose = document.getElementById('lightbox-close');

    window.galleryGoTo = function(idx) {
        if (idx < 0 || idx >= total) return;
        imgs[current].style.display = 'none';
        thumbs[current] && thumbs[current].classList.remove('active');
        current = idx;
        imgs[current].style.display = 'block';
        thumbs[current] && thumbs[current].classList.add('active');
        if (counter) counter.textContent = (current + 1) + ' / ' + total;

        // Scroll thumbnail into view
        if (thumbs[current]) {
            thumbs[current].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    };

    window.gallerySlide = function(dir) {
        galleryGoTo((current + dir + total) % total);
    };

    // Click image → open lightbox
    imgs.forEach(function(img) {
        img.addEventListener('click', function() {
            if (!lightbox) return;
            lightboxImg.src = this.src;
            lightbox.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close lightbox on background click
    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }

    // Close button
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox && lightbox.classList.contains('open')) {
            if (e.key === 'Escape') closeLightbox();
            return;
        }
        if (e.key === 'ArrowLeft') gallerySlide(-1);
        if (e.key === 'ArrowRight') gallerySlide(1);
    });
})();
</script>

@endsection
