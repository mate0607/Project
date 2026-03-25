@extends('layouts.app')


@section('content')

@php
    $statusLabels = [
        'pending' => 'Függőben',
        'confirmed' => 'Megerősítve',
        'in_progress' => 'Folyamatban',
        'completed' => 'Befejezve',
        'cancelled' => 'Törölve',
    ];

    $stages = ['received', 'inspected', 'in_progress', 'ready'];
    $stageLabels = [
        'received' => 'Autó átvéve',
        'inspected' => 'Állapotfelmérés kész',
        'in_progress' => 'Szerelés / alkatrészre vár',
        'ready' => 'Kész, elvihető',
    ];
    $stageIcons = [
        'received' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17H5a2 2 0 0 0-2 2m4-2a2 2 0 1 1-4 0m4 0h6m6 0a2 2 0 0 1-2 2m2-2a2 2 0 1 0-4 0m4 0H15m0 0a2 2 0 1 0-4 0m-1-4 1.3-5.1A2 2 0 0 1 13.24 9.4h0"/></svg>',
        'inspected' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>',
        'in_progress' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'ready' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>',
    ];

    $currentStageIndex = $appointment->service_stage ? array_search($appointment->service_stage, $stages) : -1;
    $isCompleted = $appointment->status === 'completed';
    $isCancelled = $appointment->status === 'cancelled';
    $isUpcoming = in_array($appointment->status, ['pending', 'confirmed']);
    $isInProgress = $appointment->status === 'in_progress' || ($appointment->service_stage && !$isCompleted && !$isCancelled);

    $datePart = $appointment->date instanceof \Carbon\CarbonInterface
        ? $appointment->date->toDateString()
        : \Carbon\Carbon::parse((string) $appointment->date)->toDateString();
    $timePart = \Carbon\Carbon::parse((string) $appointment->time)->format('H:i');
@endphp

<section class="apps-shell apps-page-enter">
    {{-- Fejléc --}}
    <div class="svc-detail-head">
        <div>
            <a href="{{ route('appointments.index') }}" class="car-back-arrow" title="Vissza">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            </a>
            <h1 class="page-title">Szerviz #{{ $appointment->id }}</h1>
            <p class="page-subtitle">{{ $appointment->car?->make_model ?? 'Ismeretlen autó' }}</p>
        </div>
        <span class="svc-badge svc-badge-{{ $appointment->status }} svc-badge-lg">{{ $statusLabels[$appointment->status] ?? strtoupper($appointment->status) }}</span>
    </div>

    {{-- Alap adatok --}}
    <div class="svc-info-grid">
        <div class="svc-info-card">
            <span class="svc-info-label">Dátum</span>
            <strong>{{ \Carbon\Carbon::parse($datePart)->format('Y.m.d') }}</strong>
        </div>
        <div class="svc-info-card">
            <span class="svc-info-label">Időpont</span>
            <strong>{{ $timePart }}</strong>
        </div>
        <div class="svc-info-card">
            <span class="svc-info-label">Szerviz típus</span>
            <strong>{{ $appointment->service ?: 'Általános szerviz' }}</strong>
        </div>
        <div class="svc-info-card">
            <span class="svc-info-label">Autó</span>
            <strong>{{ $appointment->car?->make_model ?? '—' }}</strong>
        </div>
    </div>

    @if($appointment->description)
        <div class="svc-desc-card">
            <span class="svc-info-label">Megjegyzés</span>
            <p>{{ $appointment->description }}</p>
        </div>
    @endif

    {{-- 4 lépéses szerviz folyamat tracker --}}
    @if($isInProgress || $isCompleted)
        <div class="svc-progress-wrap">
            <h2 class="svc-section-title">Szerviz folyamat</h2>
            <div class="svc-progress-bar">
                @foreach($stages as $i => $stage)
                    @php
                        $done = $isCompleted || $i <= $currentStageIndex;
                        $active = !$isCompleted && $i === $currentStageIndex;
                    @endphp
                    <div class="svc-step {{ $done ? 'svc-step-done' : '' }} {{ $active ? 'svc-step-active' : '' }}">
                        <div class="svc-step-icon">{!! $stageIcons[$stage] !!}</div>
                        <div class="svc-step-label">{{ $stageLabels[$stage] }}</div>
                        <div class="svc-step-num">{{ $i + 1 }}.</div>
                    </div>
                    @if($i < count($stages) - 1)
                        <div class="svc-step-line {{ ($isCompleted || $i < $currentStageIndex) ? 'svc-step-line-done' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Befejezett szerviz részletei --}}
    @if($isCompleted)
        <div class="svc-completed-wrap">
            <h2 class="svc-section-title">Szerviz eredmény</h2>

            <div class="svc-result-grid">
                @if($appointment->total_cost)
                    <div class="svc-result-card">
                        <span class="svc-info-label">Végösszeg</span>
                        <strong class="svc-cost">{{ number_format($appointment->total_cost, 0, ',', ' ') }} Ft</strong>
                    </div>
                @endif
                @if($appointment->mechanic_name)
                    <div class="svc-result-card">
                        <span class="svc-info-label">Szerelő</span>
                        <strong>{{ $appointment->mechanic_name }}</strong>
                    </div>
                @endif
            </div>

            @if($appointment->service_report)
                <div class="svc-report-card">
                    <span class="svc-info-label">Elvégzett munkák</span>
                    <p>{{ $appointment->service_report }}</p>
                </div>
            @endif

            @if($appointment->issues_found)
                <div class="svc-report-card svc-report-info">
                    <span class="svc-info-label">🔧 Talált hibák / megjegyzések</span>
                    <p>{{ $appointment->issues_found }}</p>
                </div>
            @endif

            @if($appointment->critical_warning)
                <div class="svc-report-card svc-report-critical">
                    <span class="svc-info-label">⚠️ Kritikus figyelmeztetés</span>
                    <p>{{ $appointment->critical_warning }}</p>
                </div>
            @endif

            {{-- Szerviz fotók --}}
            @if($appointment->servicePhotos && $appointment->servicePhotos->count() > 0)
                <div class="svc-photos-wrap">
                    <h3 class="svc-section-title">Szerviz fotók</h3>
                    <div class="svc-photos-grid">
                        @foreach($appointment->servicePhotos as $photo)
                            <div class="svc-photo-card">
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">
                                <span>{{ $photo->title }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Törölve --}}
    @if($isCancelled)
        <div class="svc-cancelled-notice">
            <p>Ez az időpont törölve lett.</p>
        </div>
    @endif
</section>

@endsection