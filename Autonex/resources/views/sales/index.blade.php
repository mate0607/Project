@extends('layouts.app')

@section('content')

<section class="sales-hero">
    <div>
        <p class="sales-kicker">Sales Board</p>
        <h1 class="page-title">Eladások</h1>
        <p class="page-subtitle">Áttekinthető ajánlatlista, állapot és részletek egy oldalon.</p>
    </div>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('sales.create') }}" class="btn sale-btn-main">+ Új eladás</a>
    @endif
</section>

<section class="sales-layout">
    <aside class="sales-summary card">
        <h3>Gyors összegzés</h3>
        <div class="sales-metric">
            <span>Aktív hirdetések</span>
            <strong>{{ $sales->where('is_active', true)->count() }}</strong>
        </div>
        <div class="sales-metric">
            <span>Összes eladás</span>
            <strong>{{ $sales->count() }}</strong>
        </div>
        <div class="sales-metric">
            <span>Top ár</span>
            <strong>{{ number_format((float) ($sales->max('price') ?? 0), 0, ',', ' ') }} Ft</strong>
        </div>
    </aside>

    <div class="sales-main card">
        @forelse($sales as $sale)
            <article class="sale-row">
                <div class="sale-row-main">
                    <h3>#{{ $sale->id }} · {{ $sale->car?->make_model ?? 'Ismeretlen autó' }}</h3>
                    <p>{{ $sale->description ?: 'Nincs leírás megadva.' }}</p>
                </div>

                <div class="sale-row-meta">
                    <div class="sale-chip">{{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</div>
                    <div class="sale-chip sale-chip-soft">{{ $sale->car_condition ?? 'Állapot: n/a' }}</div>
                    <div class="sale-chip {{ $sale->is_active ? 'sale-chip-active' : 'sale-chip-inactive' }}">
                        {{ $sale->is_active ? 'AKTÍV' : 'INAKTÍV' }}
                    </div>
                </div>

                <div class="sale-row-actions">
                    <a href="{{ route('sales.show', $sale) }}" class="btn-small sale-btn">Megnyit</a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('sales.edit', $sale) }}" class="btn-small sale-btn">Szerkeszt</a>

                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small sale-btn-delete">Törlés</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <p class="empty-state">Még nincs rögzített eladás.</p>
        @endforelse
    </div>
</section>

@endsection
