@extends('layouts.app')

@section('content')

@php
    $stageLabels = [
        'received'    => 'Átvéve',
        'inspected'   => 'Átvizsgálva',
        'in_progress' => 'Szerelés alatt',
        'ready'       => 'Kész, elvihető',
    ];

    $statusLabels = [
        'pending'     => 'Függőben',
        'confirmed'   => 'Megerősítve',
        'in_progress' => 'Folyamatban',
        'completed'   => 'Befejezve',
        'cancelled'   => 'Lemondva',
    ];
@endphp

<section class="ws-page">

    {{-- Header with title + edit button --}}
    <div class="ws-header">
        <div>
            <h1 class="ws-title">Munkalap</h1>
            <p class="ws-subtitle">{{ $appointment->work_number ?? '—' }}</p>
        </div>
        <a href="{{ route('admin.appointments.edit', $appointment) }}" class="ws-edit-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Szerkesztés
        </a>
    </div>

    {{-- Worksheet card --}}
    <div class="ws-card">

        {{-- Status badges row --}}
        <div class="ws-badges">
            <span class="ad-badge ad-badge-{{ $appointment->status }}">
                {{ $statusLabels[$appointment->status] ?? strtoupper($appointment->status) }}
            </span>
            @if($appointment->service_stage)
                <span class="ws-stage-badge ws-stage-{{ $appointment->service_stage }}">
                    {{ $stageLabels[$appointment->service_stage] ?? $appointment->service_stage }}
                </span>
            @endif
        </div>

        {{-- Info grid --}}
        <div class="ws-grid">

            <div class="ws-field">
                <span class="ws-label">Ügyfél</span>
                <span class="ws-value">{{ $appointment->user?->name ?? '—' }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Telefon</span>
                <span class="ws-value">{{ $appointment->user?->phone ?? 'Nincs megadva' }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Autó</span>
                <span class="ws-value">{{ $appointment->car?->make_model ?? '—' }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Rendszám</span>
                <span class="ws-value ws-value-mono">{{ $appointment->car?->license_plate ?? '—' }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Dátum</span>
                <span class="ws-value">{{ \Carbon\Carbon::parse($appointment->date)->format('Y. m. d.') }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Időpont</span>
                <span class="ws-value">{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</span>
            </div>

            <div class="ws-field">
                <span class="ws-label">Szerelő</span>
                <span class="ws-value">{{ $appointment->mechanic_name ?? 'Nincs kijelölve' }}</span>
            </div>

            @if($appointment->total_cost)
            <div class="ws-field">
                <span class="ws-label">Összköltség</span>
                <span class="ws-value ws-value-highlight">{{ number_format($appointment->total_cost, 0, ',', ' ') }} Ft</span>
            </div>
            @endif

        </div>

        {{-- Description --}}
        @if($appointment->description)
        <div class="ws-section">
            <h3 class="ws-section-title">Leírás</h3>
            <p class="ws-text">{{ $appointment->description }}</p>
        </div>
        @endif

        {{-- Service report --}}
        @if($appointment->service_report)
        <div class="ws-section">
            <h3 class="ws-section-title">Szerviz jelentés</h3>
            <p class="ws-text">{{ $appointment->service_report }}</p>
        </div>
        @endif

        {{-- Issues found --}}
        @if($appointment->issues_found)
        <div class="ws-section">
            <h3 class="ws-section-title">Talált hibák</h3>
            <p class="ws-text">{{ $appointment->issues_found }}</p>
        </div>
        @endif

        {{-- Critical warning --}}
        @if($appointment->critical_warning)
        <div class="ws-section ws-section-warning">
            <h3 class="ws-section-title">⚠ Kritikus figyelmeztetés</h3>
            <p class="ws-text">{{ $appointment->critical_warning }}</p>
        </div>
        @endif

    </div>

    {{-- Back link --}}
    <a href="{{ route('admin.dashboard') }}" class="ws-back">← Vissza a dashboardra</a>

</section>

@endsection
