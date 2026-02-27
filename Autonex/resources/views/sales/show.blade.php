@extends('layouts.app')

@section('content')

<section class="sales-hero sales-hero-tight">
    <div>
        <p class="sales-kicker">Sale Detail</p>
        <h1 class="page-title">Eladás #{{ $sale->id }}</h1>
    </div>
    <div class="form-actions" style="margin-top: 0;">
        <a href="{{ route('sales.edit', $sale) }}" class="btn sale-btn-main">Szerkesztés</a>
        <a href="{{ route('sales.index') }}" class="btn btn-muted">Vissza</a>
    </div>
</section>

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

@endsection
