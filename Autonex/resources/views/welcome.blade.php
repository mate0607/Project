@extends('layouts.app')

@section('content')

<section class="hero">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Átlátható jármű- és szervizkezelés egy modern, gyors felületen.</p>
    </div>

    <div class="action-row hero-actions">
        @guest
            <a href="{{ route('login') }}" class="btn btn-muted">Belépés</a>
            <a href="{{ route('register') }}" class="btn">Regisztráció</a>
        @else
            <a href="{{ route('cars.create') }}" class="btn">+ Új autó hozzáadása</a>
        @endguest
    </div>
</section>

<div class="stat-grid">
    <div class="stat-card">
        <h3>Autók</h3>
        <p>{{ $carCount }}</p>
    </div>

    <div class="stat-card">
        <h3>Hibák</h3>
        <p>0</p>
    </div>

    <div class="stat-card">
        <h3>Időpontok</h3>
        <p>0</p>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3 style="margin-bottom: 10px;">Gyors műveletek</h3>
    <div class="action-row">
        <a href="{{ route('cars.index') }}" class="btn-small">Autók listája</a>
        <a href="{{ url('/issues') }}" class="btn-small">Hibák kezelése</a>
        <a href="{{ url('/appointments') }}" class="btn-small">Időpontok</a>
    </div>
</div>

@endsection