@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

{{-- Admin dashboard: rendszer-szintu mutatok es operativ attekintes --}}
<section class="admin-dashboard">
    <header class="ad-hero">
        <h1>Rendszer áttekintés</h1>
    </header>

    <section class="ad-stats-grid">
        <article class="ad-stat-card">
            <span class="ad-stat-label">Autók száma</span>
            <strong class="ad-stat-value">{{ $stats['cars'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Felhasználók száma</span>
            <strong class="ad-stat-value">{{ $stats['users'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Hibák száma</span>
            <strong class="ad-stat-value">{{ $stats['issues'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Időpontok száma</span>
            <strong class="ad-stat-value">{{ $stats['appointments'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Mai időpontok</span>
            <strong class="ad-stat-value">{{ $stats['todayAppointments'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Pending időpontok</span>
            <strong class="ad-stat-value">{{ $stats['pendingAppointments'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Confirmed időpontok</span>
            <strong class="ad-stat-value">{{ $stats['confirmedAppointments'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Függő hibajegyek</span>
            <strong class="ad-stat-value">{{ $stats['pendingIssues'] }}</strong>
        </article>

        <article class="ad-stat-card">
            <span class="ad-stat-label">Befejezett szervizek</span>
            <strong class="ad-stat-value">{{ $stats['completedServices'] }}</strong>
        </article>
    </section>

    <section class="ad-grid-2col">
        <article class="ad-card">
            <h2>Legutóbbi aktivitások</h2>
            <div class="ad-activity-list">
                {{-- Az activity elemeket a controller eloallitott strukturaja hajtja meg. --}}
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
                <a href="{{ route('cars.create') }}" class="ad-btn">Új autó hozzáadása</a>
                <a href="{{ route('issues.create') }}" class="ad-btn">Új hibajegy</a>
                <a href="{{ route('appointments.create') }}" class="ad-btn">Új időpont</a>
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
        // A chart inicializalas csak akkor fusson, ha a canvas es a Chart library is elerheto.
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
