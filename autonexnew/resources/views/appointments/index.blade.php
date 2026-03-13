@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/appointments-schedule.css') }}">
@endpush

@section('content')

@php
    $now = now();

    $toDateTime = function ($appointment) {
        $datePart = $appointment->date instanceof \Carbon\CarbonInterface
            ? $appointment->date->toDateString()
            : \Carbon\Carbon::parse((string) $appointment->date)->toDateString();
        $timePart = \Carbon\Carbon::parse((string) $appointment->time)->format('H:i:s');
        return \Carbon\Carbon::parse($datePart . ' ' . $timePart);
    };

    $formatDate = fn ($a) => $toDateTime($a)->format('Y.m.d');
    $formatTime = fn ($a) => $toDateTime($a)->format('H:i');

    $nextAppointment = $appointments
        ->filter(fn ($a) => $toDateTime($a)->greaterThanOrEqualTo($now) && !in_array($a->status, ['cancelled', 'completed']))
        ->sortBy(fn ($a) => $toDateTime($a)->timestamp)
        ->first();

    $sorted = $appointments->sortByDesc(fn ($a) => $toDateTime($a)->timestamp);

    $statusLabels = [
        'pending' => 'Függőben',
        'confirmed' => 'Megerősítve',
        'in_progress' => 'Folyamatban',
        'completed' => 'Befejezve',
        'cancelled' => 'Törölve',
    ];

    $stageLabels = [
        'received' => 'Átvéve',
        'inspected' => 'Átvizsgálva',
        'in_progress' => 'Szerelés alatt',
        'ready' => 'Kész, elvihető',
    ];
@endphp

<section class="apps-shell apps-page-enter">
    <header class="apps-hero">
        <div>
            <h1 class="page-title">Időpontjaim</h1>
            <p class="page-subtitle">Szerviz időpontjaid és azok aktuális állapota egy helyen.</p>
        </div>

        <a href="{{ route('appointments.create') }}" class="btn apps-book-btn">
            <span class="apps-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
            </span>
            <span>Időpont foglalása</span>
        </a>
    </header>

    {{-- Legközelebbi időpont kiemelt kártya --}}
    @if($nextAppointment)
        <a href="{{ route('appointments.show', $nextAppointment) }}" class="svc-next-card">
            <div class="svc-next-label">Legközelebbi időpont</div>
            <div class="svc-next-main">
                <h2>{{ $nextAppointment->car?->make_model ?? 'Nincs autó' }}</h2>
                <p>{{ $nextAppointment->service ?: 'Általános szerviz' }}</p>
            </div>
            <div class="svc-next-meta">
                <span>{{ $formatDate($nextAppointment) }}</span>
                <span>{{ $formatTime($nextAppointment) }}</span>
                <span class="svc-badge svc-badge-{{ $nextAppointment->status }}">{{ $statusLabels[$nextAppointment->status] ?? strtoupper($nextAppointment->status) }}</span>
            </div>
            @if($nextAppointment->service_stage)
                <div class="svc-next-stage">
                    <span>Szerviz állapot:</span>
                    <strong>{{ $stageLabels[$nextAppointment->service_stage] ?? $nextAppointment->service_stage }}</strong>
                </div>
            @endif
        </a>
    @endif

    {{-- Összes szerviz táblázat --}}
    <div class="svc-table-wrap">
        <table class="svc-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autó</th>
                    <th>Dátum</th>
                    <th>Időpont</th>
                    <th>Szerviz</th>
                    <th>Státusz</th>
                    <th>Szerviz állapot</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sorted as $appointment)
                    <tr class="svc-table-row" onclick="window.location='{{ route('appointments.show', $appointment) }}'">
                        <td><span class="svc-id">#{{ $appointment->id }}</span></td>
                        <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $formatDate($appointment) }}</td>
                        <td>{{ $formatTime($appointment) }}</td>
                        <td>{{ $appointment->service ?: 'Általános' }}</td>
                        <td><span class="svc-badge svc-badge-{{ $appointment->status }}">{{ $statusLabels[$appointment->status] ?? strtoupper($appointment->status) }}</span></td>
                        <td>
                            @if($appointment->service_stage)
                                <span class="svc-stage-chip">{{ $stageLabels[$appointment->service_stage] ?? $appointment->service_stage }}</span>
                            @else
                                <span class="svc-stage-chip svc-stage-none">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="svc-empty">Nincs még rögzített időpont.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection