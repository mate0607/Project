@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@section('content')

@php
    $latestCarPreview = [
        'name' => 'BMW 320d Touring',
        'plate' => 'ABC-123',
        'last_service' => '2026-01-14',
    ];

    $serviceSteps = [
        'Időpont foglalva',
        'Elfogadva',
        'Szerviz alatt',
        'Kész',
    ];

    $activeServiceStep = 2;

    $notifications = [
        ['icon' => '📅', 'text' => 'Időpont megerősítve'],
        ['icon' => '🛠️', 'text' => 'Hibajegy frissítve'],
        ['icon' => '✅', 'text' => 'Szerviz elkészült'],
    ];
@endphp

<section class="user-dashboard">
    <header class="ud-hero fade-in">
        <div class="ud-hero-content">
            <p class="ud-kicker">Autonex</p>
            <h1>Saját felületem</h1>
            <p>Autóid, hibajegyeid és időpontjaid egy helyen.</p>

            <div class="ud-hero-actions">
                <a href="{{ route('appointments.create') }}" class="ud-action-btn ud-action-btn-primary">Új időpont foglalása</a>
                <a href="{{ route('cars.index') }}" class="ud-action-btn ud-action-btn-soft">Saját autóim</a>
            </div>
        </div>

        <div class="ud-hero-glow" aria-hidden="true"></div>
    </header>

    <section class="ud-stats-grid">
        <article class="ud-stat-card fade-in delay-1">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 16H9m10 0h2m-2 0a2 2 0 1 0 2 2m-2-2a2 2 0 1 1-2 2M5 16H3m2 0a2 2 0 1 1-2 2m2-2a2 2 0 1 0 2 2m12-2v-4a2 2 0 0 0-1.1-1.79l-2.57-1.28A2 2 0 0 0 14.45 9H9.55a2 2 0 0 0-.89.21L6.1 10.5A2 2 0 0 0 5 12.29V16"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $carsCount }}</div>
            <div class="ud-stat-label">Autóim száma</div>
        </article>

        <article class="ud-stat-card fade-in delay-2">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 2v4M16 2v4M3 10h18"/>
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $appointmentsCount }}</div>
            <div class="ud-stat-label">Időpontjaim száma</div>
        </article>

        <article class="ud-stat-card fade-in delay-3">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $issuesCount }}</div>
            <div class="ud-stat-label">Hibajegyeim száma</div>
        </article>

        <article class="ud-stat-card fade-in delay-4">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $servicesCount }}</div>
            <div class="ud-stat-label">Szervizek száma</div>
        </article>
    </section>

    <section class="ud-car-preview fade-in delay-2">
        <div class="ud-car-preview-main">
            <div class="ud-car-preview-head">
                <span class="ud-car-preview-icon" aria-hidden="true">
                    <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 16H9m10 0h2m-2 0a2 2 0 1 0 2 2m-2-2a2 2 0 1 1-2 2M5 16H3m2 0a2 2 0 1 1-2 2m2-2a2 2 0 1 0 2 2m12-2v-4a2 2 0 0 0-1.1-1.79l-2.57-1.28A2 2 0 0 0 14.45 9H9.55a2 2 0 0 0-.89.21L6.1 10.5A2 2 0 0 0 5 12.29V16"/>
                    </svg>
                </span>
                <h2>Legutóbb használt autóm</h2>
            </div>

            <div class="ud-car-preview-grid">
                <div>
                    <small>Autó neve</small>
                    <strong>{{ $latestCarPreview['name'] }}</strong>
                </div>
                <div>
                    <small>Rendszám</small>
                    <strong>{{ $latestCarPreview['plate'] }}</strong>
                </div>
                <div>
                    <small>Utolsó szerviz dátuma</small>
                    <strong>{{ $latestCarPreview['last_service'] }}</strong>
                </div>
            </div>
        </div>

        <div class="ud-car-preview-action">
            <a href="{{ route('cars.index') }}" class="ud-action-btn ud-action-btn-primary">Autó megnyitása</a>
        </div>
    </section>

    <section class="ud-next-card fade-in delay-2">
        <div class="ud-next-head">
            <h2>Következő időpont</h2>
        </div>

        @if($nextAppointment)
            <div class="ud-next-grid">
                <div class="ud-next-item">
                    <span>Autó</span>
                    <strong>{{ $nextAppointment->car?->make_model ?? 'Nincs autó' }}</strong>
                </div>
                <div class="ud-next-item">
                    <span>Dátum</span>
                    <strong>{{ $nextAppointment->date }}</strong>
                </div>
                <div class="ud-next-item">
                    <span>Idő</span>
                    <strong>{{ $nextAppointment->time }}</strong>
                </div>
                <div class="ud-next-item">
                    <span>Státusz</span>
                    <strong class="ud-badge ud-badge-{{ $nextAppointment->status }}">{{ strtoupper($nextAppointment->status) }}</strong>
                </div>
            </div>
        @else
            <p class="ud-empty">Nincs közelgő időpontod.</p>
        @endif
    </section>

    <section class="ud-timeline-card fade-in delay-3">
        <div class="ud-timeline-head">
            <h2>Szerviz állapot</h2>
        </div>

        <div class="ud-timeline" role="list">
            @foreach($serviceSteps as $index => $step)
                <div class="ud-timeline-step {{ $index <= $activeServiceStep ? 'is-done' : '' }} {{ $index === $activeServiceStep ? 'is-active' : '' }}" role="listitem">
                    <span class="ud-timeline-dot"></span>
                    <span class="ud-timeline-label">{{ $step }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="ud-actions fade-in delay-3">
        <h2>Gyors műveletek</h2>
        <div class="ud-actions-row">
            <a href="{{ route('appointments.create') }}" class="ud-action-btn ud-action-btn-primary">Új időpont</a>
            <a href="{{ route('issues.create') }}" class="ud-action-btn ud-action-btn-violet">Új hibajegy</a>
            <a href="{{ route('cars.create') }}" class="ud-action-btn ud-action-btn-cyan">Új autó</a>
        </div>
    </section>

    <section class="ud-notifications-card fade-in delay-3">
        <div class="ud-table-head">
            <h2>Értesítések</h2>
        </div>

        @if(count($notifications) > 0)
            <div class="ud-notifications-list">
                @foreach($notifications as $notification)
                    <div class="ud-notification-item">
                        <span class="ud-notification-icon" aria-hidden="true">{{ $notification['icon'] }}</span>
                        <span class="ud-notification-text">{{ $notification['text'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="ud-empty">Még nincs értesítésed</p>
        @endif
    </section>

    <section class="ud-chart-card fade-in delay-4">
        <div class="ud-table-head">
            <h2>Aktivitás</h2>
        </div>
        <div class="ud-chart-wrap">
            <canvas id="activityChart" aria-label="Havi aktivitás" role="img"></canvas>
        </div>
    </section>

    @if($appointments->count() === 0)
        <section class="ud-empty-card fade-in delay-4">
            <div class="ud-empty-icon" aria-hidden="true">📭</div>
            <h3>Még nincs időpontod</h3>
            <p>Foglalj most egyet</p>
            <a href="{{ route('appointments.create') }}" class="ud-action-btn ud-action-btn-primary">Új időpont</a>
        </section>
    @endif

    <section class="ud-table-card fade-in delay-4">
        <div class="ud-table-head">
            <h2>Saját időpontok</h2>
        </div>

        <div class="ud-table-wrap">
            <table class="ud-table">
                <thead>
                    <tr>
                        <th>Autó</th>
                        <th>Dátum</th>
                        <th>Idő</th>
                        <th>Státusz</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                            <td>{{ $appointment->date }}</td>
                            <td>{{ $appointment->time }}</td>
                            <td>
                                <span class="ud-badge ud-badge-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="ud-table-btn">Megnyit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="ud-empty">Még nincs időpontod.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const chartElement = document.getElementById('activityChart');

        if (!chartElement || typeof Chart === 'undefined') {
            return;
        }

        new Chart(chartElement, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Máj', 'Jún'],
                datasets: [{
                    label: 'Időpontok',
                    data: [2, 3, 4, 3, 5, 6],
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(59, 130, 246, 0.22)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#93c5fd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { color: 'rgba(148, 163, 184, 0.14)' },
                        ticks: { color: '#bfd4ff' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.14)' },
                        ticks: { color: '#bfd4ff', stepSize: 1 }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#dbeafe' }
                    }
                }
            }
        });
    })();
</script>

@endsection

@section('page_footer')
<footer class="ud-site-footer fade-in delay-4">
    <div class="ud-site-footer-inner">
        <div class="ud-footer-brand">
            <span class="ud-footer-logo">Autonex</span>
            <p>Modern autós szerviz management platform.</p>
        </div>

        <nav class="ud-footer-links" aria-label="Footer navigáció">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <a href="{{ route('cars.index') }}">Autóim</a>
            <a href="{{ route('issues.index') }}">Hibajegyek</a>
            <a href="{{ route('appointments.index') }}">Időpontok</a>
        </nav>

        <div class="ud-footer-contact">
            <p>Kapcsolat: support@autonex.hu</p>
            <small>© {{ now()->year }} Autonex. Minden jog fenntartva.</small>
        </div>
    </div>
</footer>
@endsection
