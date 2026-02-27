@extends('layouts.app')

@section('content')

<div class="page-head cars-page-head">
    <h1 class="page-title">Autó adatai</h1>
    <a href="{{ route('cars.index') }}" class="btn btn-muted">Vissza</a>
</div>

<div class="cars-split-layout">
    <div class="card detail-card car-detail-card">
        <div class="detail-row">
            <span class="detail-label">ID</span>
            <span class="detail-value">{{ $car->id }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Típus</span>
            <span class="detail-value">{{ $car->make_model }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">VIN</span>
            <span class="detail-value">{{ $car->vin ?? 'Nincs megadva' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Év</span>
            <span class="detail-value">{{ $car->year ?? 'Nincs megadva' }}</span>
        </div>

        <div class="form-actions" style="margin-top: 18px;">
            <a href="{{ route('cars.edit', $car) }}" class="btn car-btn-main">Szerkesztés</a>
        </div>
    </div>

    <aside class="card cars-side-panel">
        <h3>Gyors infó</h3>
        <p>Az autó adatai itt ellenőrizhetők szerkesztés előtt.</p>
        <p>Ha VIN hiányzik, érdemes pótolni.</p>
    </aside>
</div>

@endsection