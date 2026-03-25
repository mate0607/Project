@extends('layouts.app')

@section('content')

<section class="sales-hero sales-hero-tight">
    <div>
        <p class="sales-kicker">Sale Detail</p>
        <h1 class="page-title">Eladás #{{ $sale->id }}</h1>
    </div>
    <div class="form-actions" style="margin-top: 0;">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('sales.edit', $sale) }}" class="btn sale-btn-main">Szerkesztés</a>
        @endif
        <a href="{{ route('sales.index') }}" class="btn btn-muted">Vissza</a>
    </div>
</section>

@if($sale->images->count())
    <div class="card" style="margin-bottom:16px;padding:0;overflow:hidden;position:relative;">
        <div class="sale-carousel" id="carousel-show">
            @foreach($sale->images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $sale->car?->make_model }}" class="sale-carousel-img" style="width:100%;max-height:520px;object-fit:contain;display:{{ $loop->first ? 'block' : 'none' }};background:#0b1220;">
            @endforeach
        </div>
        @if($sale->images->count() > 1)
            <button type="button" class="carousel-btn carousel-prev" onclick="slideCarousel('carousel-show',-1)">&#10094;</button>
            <button type="button" class="carousel-btn carousel-next" onclick="slideCarousel('carousel-show',1)">&#10095;</button>
            <div style="text-align:center;padding:8px 0;background:rgba(0,0,0,.03);">
                <span class="carousel-counter" id="carousel-show-counter">1 / {{ $sale->images->count() }}</span>
            </div>
        @endif
    </div>
@elseif($sale->image)
    <div class="card" style="margin-bottom:16px;padding:0;overflow:hidden;">
        <img src="{{ asset('storage/' . $sale->image) }}" alt="{{ $sale->car?->make_model }}" style="width:100%;max-height:520px;object-fit:contain;display:block;background:#0b1220;">
    </div>
@endif

<section class="sales-detail-layout">
    <div class="card sales-detail-main">
        <h3>Fő adatok</h3>
        <div class="sales-detail-grid">
            <div class="sales-detail-item">
                <small>Autó</small>
                <strong>{{ $sale->car?->make_model ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Vevő</small>
                <strong>{{ $sale->buyer?->name ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Eladó</small>
                <strong>{{ $sale->seller?->name ?? '—' }}</strong>
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

@endsection
