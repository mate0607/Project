@extends('layouts.app')

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

    $nextAppointment = $appointments
        ->filter(fn ($a) => $toDateTime($a)->greaterThanOrEqualTo($now) && !in_array($a->status, ['cancelled', 'completed']))
        ->sortBy(fn ($a) => $toDateTime($a)->timestamp)
        ->first();

    $sorted = $appointments->sortByDesc(fn ($a) => $toDateTime($a)->timestamp);

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

    <div class="svc-table-wrap">
        <table class="svc-table svc-table-fixed">
            <thead>
                <tr>
                    <th style="width:120px;">Munkalap</th>
                    <th style="width:180px;">Autó</th>
                    <th style="width:150px;">Időpont</th>
                    <th style="width:160px;">Szerviz</th>
                    <th style="width:140px;">Szerviz állapot</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sorted as $appointment)
                    <tr class="svc-table-row" onclick="window.location='{{ route('appointments.show', $appointment) }}'">
                        <td><span class="svc-id">{{ $appointment->work_number ?? '—' }}</span></td>
                        <td class="svc-cell-truncate" title="{{ $appointment->car?->make_model ?? '—' }}">{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $toDateTime($appointment)->format('Y.m.d H:i') }}</td>
                        <td class="svc-cell-truncate" title="{{ $appointment->service ?: 'Általános' }}">{{ $appointment->service ?: 'Általános' }}</td>
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
                        <td colspan="5" class="svc-empty">Nincs még rögzített időpont.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection