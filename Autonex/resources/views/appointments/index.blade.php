@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/appointments-schedule.css') }}">
@endpush

@section('content')

@php
    // Az idopontokat datum+ido alapjan kezeljuk esemenykent.
    $now = now();

    // A date cast miatt erkezhet teljes datetime is, ezert itt mindig tiszta Y-m-d + H:i:s formatot allitunk elo.
    $toDateTime = function ($appointment) {
        $datePart = $appointment->date instanceof \Carbon\CarbonInterface
            ? $appointment->date->toDateString()
            : \Carbon\Carbon::parse((string) $appointment->date)->toDateString();

        $timePart = \Carbon\Carbon::parse((string) $appointment->time)->format('H:i:s');

        return \Carbon\Carbon::parse($datePart . ' ' . $timePart);
    };

    $formatDate = fn ($appointment) => $toDateTime($appointment)->format('Y.m.d');
    $formatTime = fn ($appointment) => $toDateTime($appointment)->format('H:i');

    $upcomingAppointments = $appointments
        ->filter(fn ($appointment) => $toDateTime($appointment)->greaterThanOrEqualTo($now))
        ->sortBy(fn ($appointment) => $toDateTime($appointment)->timestamp);

    $pastAppointments = $appointments
        ->filter(fn ($appointment) => $toDateTime($appointment)->lessThan($now))
        ->sortByDesc(fn ($appointment) => $toDateTime($appointment)->timestamp);
@endphp

<section class="apps-shell apps-page-enter">
    <header class="apps-hero">
        <div>
            <h1 class="page-title">Időpontjaim</h1>
            <p class="page-subtitle">Kezeld a közelgő és korábbi szerviz időpontokat egy eseményközpontú, áttekinthető nézetben.</p>
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

    <section class="apps-stats" aria-label="Időpont statisztikák">
        <article><p>Közelgő</p><strong>{{ $upcomingAppointments->count() }}</strong></article>
        <article><p>Korábbi</p><strong>{{ $pastAppointments->count() }}</strong></article>
        <article><p>Összes</p><strong>{{ $appointments->count() }}</strong></article>
    </section>

    <section class="apps-columns">
        <article class="apps-panel apps-upcoming">
            <header class="apps-panel-head">
                <h2>Közelgő időpontok</h2>
                <span>{{ $upcomingAppointments->count() }}</span>
            </header>

            <div class="apps-timeline" aria-label="Közelgő események">
                @forelse($upcomingAppointments as $appointment)
                    <article class="apps-event-card">
                        <div class="apps-event-dot" aria-hidden="true"></div>
                        <div class="apps-event-main">
                            <div class="apps-event-head">
                                <h3>{{ $appointment->service ?: 'Általános szerviz' }}</h3>
                                <span class="app-status app-status-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                            </div>

                            <p class="apps-event-car">{{ $appointment->car?->make_model ?? 'Nincs autó' }}</p>

                            <div class="apps-event-meta">
                                <span>{{ $formatDate($appointment) }}</span>
                                <span>{{ $formatTime($appointment) }}</span>
                            </div>

                            @if(!empty($appointment->description))
                                <p class="apps-event-desc">{{ \Illuminate\Support\Str::limit($appointment->description, 120) }}</p>
                            @endif

                            <div class="apps-event-actions">
                                <a href="{{ route('appointments.show', $appointment) }}" class="apps-link-btn">Megnyit</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="apps-empty">
                        <p>Nincs közelgő időpont.</p>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="apps-panel apps-past">
            <header class="apps-panel-head">
                <h2>Korábbi időpontok</h2>
                <span>{{ $pastAppointments->count() }}</span>
            </header>

            <div class="apps-past-list" aria-label="Korábbi események">
                @forelse($pastAppointments as $appointment)
                    <article class="apps-past-card">
                        <div>
                            <h3>{{ $appointment->service ?: 'Általános szerviz' }}</h3>
                            <p>{{ $appointment->car?->make_model ?? 'Nincs autó' }}</p>
                        </div>
                        <div class="apps-past-meta">
                            <span>{{ $formatDate($appointment) }}</span>
                            <span>{{ $formatTime($appointment) }}</span>
                        </div>
                        <a href="{{ route('appointments.show', $appointment) }}" class="apps-link-btn">Megnyit</a>
                    </article>
                @empty
                    <div class="apps-empty">
                        <p>Nincs korábbi időpont.</p>
                    </div>
                @endforelse
            </div>
        </article>
    </section>
</section>

@endsection