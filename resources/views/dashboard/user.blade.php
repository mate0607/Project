@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@section('content')



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

    <section class="ud-stats-grid" style="grid-template-columns: repeat(2, 1fr);">
        <article class="ud-stat-card fade-in delay-1">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $inServiceCount }}</div>
            <div class="ud-stat-label">Szervizben lévő autóim</div>
        </article>

        <article class="ud-stat-card fade-in delay-2">
            <div class="ud-stat-icon" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 2v4M16 2v4M3 10h18"/>
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                </svg>
            </div>
            <div class="ud-stat-value">{{ $upcomingAppointmentsCount }}</div>
            <div class="ud-stat-label">Közelgő időpontjaim</div>
        </article>
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

    <section class="ud-notifications-card fade-in delay-3">
        <div class="ud-table-head">
            <h2>Értesítések</h2>
        </div>

        @if($adminNotifications->count() > 0)
            <div class="ud-notifications-list">
                @foreach($adminNotifications as $notification)
                    <div class="ud-notification-item">
                        <span class="ud-notification-icon" aria-hidden="true">🔔</span>
                        <div>
                            <strong>{{ $notification->title }}</strong>
                            <span class="ud-notification-text">{{ $notification->message }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="ud-empty">Még nincs értesítésed</p>
        @endif
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
            <a href="{{ route('appointments.index') }}">Időpontok</a>
        </nav>

        <div class="ud-footer-contact">
            <p>Kapcsolat: support@autonex.hu</p>
            <small>© {{ now()->year }} Autonex. Minden jog fenntartva.</small>
        </div>
    </div>
</footer>
@endsection
