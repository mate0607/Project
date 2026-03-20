@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

<section class="admin-dashboard">

    {{-- 3 interaktiv kocka --}}
    <section class="ad-stats-grid ad-stats-grid-3">

        {{-- 1. Szervizben levo autok --}}
        <article class="ad-stat-card">
            <span class="ad-stat-label">Jelenleg szervizben</span>
            <strong class="ad-stat-value">{{ $inServiceCount }}</strong>
            <span class="ad-stat-sub">autó</span>
        </article>

        {{-- 2. Mai idopontok (kattinthato) --}}
        <article class="ad-stat-card ad-stat-clickable" onclick="document.getElementById('todayAppointmentsPanel').classList.toggle('ad-panel-open')">
            <span class="ad-stat-label">Mai időpontok</span>
            <strong class="ad-stat-value">{{ $todayAppointments->count() }}</strong>
            <span class="ad-stat-sub">Kattints a listáért ▾</span>
        </article>

        {{-- 3. Mai kesz autok (kattinthato) --}}
        <article class="ad-stat-card ad-stat-clickable" onclick="document.getElementById('todayCompletedPanel').classList.toggle('ad-panel-open')">
            <span class="ad-stat-label">Mai kész autók</span>
            <strong class="ad-stat-value">{{ $todayCompletedCars->count() }}</strong>
            <span class="ad-stat-sub">Kattints a listáért ▾</span>
        </article>
    </section>

    {{-- Mai idopontok lenyilo lista --}}
    <section id="todayAppointmentsPanel" class="ad-panel">
        <article class="ad-card">
            <h2>Mai időpontok</h2>
            @if($todayAppointments->count() > 0)
                <div class="ad-list">
                    @foreach($todayAppointments as $apt)
                        <a href="{{ route('admin.appointments.index') }}" class="ad-list-item">
                            <div class="ad-list-info">
                                <strong>{{ $apt->car?->make_model ?? '—' }}</strong>
                                <span>{{ $apt->user?->name ?? '—' }}</span>
                            </div>
                            <div class="ad-list-meta">
                                <span>{{ \Carbon\Carbon::parse($apt->time)->format('H:i') }}</span>
                                <span class="ad-badge ad-badge-{{ $apt->status }}">{{ strtoupper($apt->status) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="ad-empty">Nincs mai időpont.</p>
            @endif
        </article>
    </section>

    {{-- Mai kesz autok lenyilo lista --}}
    <section id="todayCompletedPanel" class="ad-panel">
        <article class="ad-card">
            <h2>Mai kész autók — átvehetők</h2>
            @if($todayCompletedCars->count() > 0)
                <div class="ad-list">
                    @foreach($todayCompletedCars as $apt)
                        <div class="ad-list-item ad-list-item-ready ad-list-item-block">
                            <div class="ad-list-info">
                                <strong>{{ $apt->car?->make_model ?? '—' }}</strong>
                                <span>Tulajdonos: {{ $apt->user?->name ?? '—' }}</span>
                                <span class="ad-list-detail">Tel: {{ $apt->user?->phone ?? 'Nincs megadva' }}</span>
                                <span class="ad-list-detail">VIN: {{ $apt->car?->vin ?? '—' }} | {{ $apt->car?->year ?? '' }}</span>
                            </div>
                            <div class="ad-list-meta">
                                <span class="ad-badge ad-badge-completed">KÉSZ</span>
                                <a href="{{ route('admin.notifications.create') }}?user_id={{ $apt->user_id }}&title={{ urlencode('Szerviz kész — ' . ($apt->car?->make_model ?? '')) }}&message={{ urlencode('Tisztelt ' . ($apt->user?->name ?? 'Ügyfél') . '! Az Ön autója (' . ($apt->car?->make_model ?? '') . ', ' . ($apt->car?->vin ?? '') . ') elkészült és átvehető. Kérjük, egyeztessen időpontot az átvételhez.') }}" class="ad-list-action-btn">Értesítés küldése →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="ad-empty">Nincs ma elkészült autó.</p>
            @endif
        </article>
    </section>

    <section class="ad-grid-2col">
        <article class="ad-card">
            <h2>Legutóbbi aktivitások</h2>
            <div class="ad-activity-list">
                @foreach($recentActivities as $activity)
                    <div class="ad-activity-item">
                        <span class="ad-activity-dot"></span>
                        <div>
                            <p>{{ $activity['label'] }}</p>
                            <small>
                                {{ $activity['item'] ? optional($activity['item']->{$activity['dateField']})->format('Y-m-d H:i') : 'Nincs adat' }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="ad-card">
            <h2>Gyors műveletek</h2>
            <div class="ad-actions">
                <a href="{{ route('appointments.create') }}" class="ad-btn">Új időpont</a>
                <a href="{{ route('admin.notifications.create') }}" class="ad-btn">Új értesítés</a>
            </div>
        </article>
    </section>

    <section class="ad-grid-2col">
        <article class="ad-card">
            <h2>Időpontok havi statisztikája</h2>
            <div class="ad-chart-wrap">
                <canvas id="adminMonthlyChart" aria-label="Admin havi időpont statisztika" role="img"></canvas>
            </div>
        </article>

        <article class="ad-card">
            <h2>Közelgő időpontok</h2>
            <div class="ad-table-wrap">
                <table class="ad-table">
                    <thead>
                        <tr>
                            <th>Autó</th>
                            <th>Felhasználó</th>
                            <th>Dátum</th>
                            <th>Idő</th>
                            <th>Státusz</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                                <td>{{ $appointment->user?->name ?? '—' }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td>{{ $appointment->time }}</td>
                                <td>
                                    <span class="ad-badge ad-badge-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="ad-empty">Nincs közelgő időpont.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const chartEl = document.getElementById('adminMonthlyChart');

        if (!chartEl || typeof Chart === 'undefined') {
            return;
        }

        new Chart(chartEl, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Időpontok',
                    data: @json($monthlyCounts),
                    backgroundColor: 'rgba(79, 124, 247, 0.28)',
                    borderColor: '#4F7CF7',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { color: 'rgba(107, 140, 255, 0.2)' },
                        ticks: { color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(107, 140, 255, 0.2)' },
                        ticks: { color: '#64748B', stepSize: 1 }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#1F2937' }
                    }
                }
            }
        });
    })();
</script>

@endsection
