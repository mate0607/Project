@extends('layouts.app')

@section('content')

<section class="hero">
    <div>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Rendszer statisztikák és admin vezérlés.</p>
    </div>
</section>

<div class="stat-grid">
    <div class="stat-card">
        <h3>Autók</h3>
        <p>{{ $stats['cars'] }}</p>
    </div>

    <div class="stat-card">
        <h3>Hibák</h3>
        <p>{{ $stats['issues'] }}</p>
    </div>

    <div class="stat-card">
        <h3>Időpontok</h3>
        <p>{{ $stats['appointments'] }}</p>
    </div>
</div>

<div class="card" style="margin-top:20px;">
    <h3 style="margin-bottom: 10px;">Admin gyors műveletek</h3>
    <div class="action-row">
        <a href="{{ route('cars.index') }}" class="btn-small">Autók kezelése</a>
        <a href="{{ route('issues.index') }}" class="btn-small">Hibák kezelése</a>
        <a href="{{ route('sales.index') }}" class="btn-small">Eladások kezelése</a>
        <a href="{{ route('appointments.index') }}" class="btn-small">Időpontok kezelése</a>
    </div>
</div>

@endsection
