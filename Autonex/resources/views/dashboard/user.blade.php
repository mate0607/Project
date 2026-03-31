@extends('layouts.app')


@section('content')

<section class="ud3-dashboard">

    {{-- Row 1: Hero — Next appointment (8 col) + Quick stats (4 col) --}}
    <div class="ud3-row ud3-hero-row fade-in">
        <div class="ud3-hero-main">
            <div class="ud3-hero-greeting">
                <span class="ud3-kicker">Dashboard</span>
                <h1>Üdvözöljük, {{ auth()->user()->name }}!</h1>
            </div>

            @if($nextAppointment)
                <a href="{{ route('appointments.show', $nextAppointment) }}" class="ud3-next-appt">
                    <div class="ud3-next-badge">Következő időpont</div>
                    <div class="ud3-next-details">
                        <div class="ud3-next-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <div>
                                <span>Dátum</span>
                                <strong>{{ \Carbon\Carbon::parse($nextAppointment->date)->format('Y.m.d') }}</strong>
                            </div>
                        </div>
                        <div class="ud3-next-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <div>
                                <span>Időpont</span>
                                <strong>{{ $nextAppointment->time }}</strong>
                            </div>
                        </div>
                        <div class="ud3-next-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2"/><path d="M9 17h6"/></svg>
                            <div>
                                <span>Autó</span>
                                <strong>{{ $nextAppointment->car?->make_model ?? '—' }}</strong>
                            </div>
                        </div>
                        <div class="ud3-next-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/></svg>
                            <div>
                                <span>Szerviz</span>
                                <strong>{{ $nextAppointment->service ?? '—' }}</strong>
                            </div>
                        </div>
                    </div>
                </a>
            @else
                <div class="ud3-next-appt ud3-next-empty">
                    <div class="ud3-next-badge">Következő időpont</div>
                    <p class="ud3-empty-text">Nincs közelgő időpontod.</p>
                </div>
            @endif
        </div>

        <div class="ud3-hero-side">
            <div class="ud3-quick-stat">
                <div class="ud3-quick-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/></svg>
                </div>
                <div class="ud3-quick-stat-info">
                    <span class="ud3-quick-stat-value">{{ $inServiceCount }}</span>
                    <span class="ud3-quick-stat-label">Szervizben</span>
                </div>
            </div>
            <div class="ud3-quick-stat">
                <div class="ud3-quick-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <div class="ud3-quick-stat-info">
                    <span class="ud3-quick-stat-value">{{ $upcomingAppointmentsCount }}</span>
                    <span class="ud3-quick-stat-label">Időpont</span>
                </div>
            </div>
            <div class="ud3-quick-stat">
                <div class="ud3-quick-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="ud3-quick-stat-info">
                    <span class="ud3-quick-stat-value">{{ $notificationsCount }}</span>
                    <span class="ud3-quick-stat-label">Értesítés</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: 3 separate quick action cards --}}
    <div class="ud3-row ud3-actions-row">
        <a href="{{ route('cars.index') }}" class="ud3-action-card fade-in delay-1">
            <div class="ud3-action-icon ud3-action-icon--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2"/><path d="M9 17h6"/></svg>
            </div>
            <strong>Autóim</strong>
            <span>Járműveim kezelése</span>
        </a>

        <a href="{{ route('appointments.create') }}" class="ud3-action-card fade-in delay-2">
            <div class="ud3-action-icon ud3-action-icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </div>
            <strong>Új időpont</strong>
            <span>Szervizelés foglalása</span>
        </a>

        <a href="{{ route('appointments.index') }}" class="ud3-action-card fade-in delay-3">
            <div class="ud3-action-icon ud3-action-icon--purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <strong>Időpontjaim</strong>
            <span>Foglalásaim áttekintése</span>
        </a>
    </div>

    {{-- Row 3: Car carousel (8 col) + Notifications (4 col) --}}
    <div class="ud3-row ud3-content-row">
        <section class="ud3-carousel-card fade-in delay-1">
            <div class="ud3-card-head">
                <h2>Legújabb eladó autók</h2>
                <a href="{{ route('sales.index') }}" class="ud3-card-link">Összes &rarr;</a>
            </div>
            @if($latestSales->count() > 0)
                <div class="ud3-carousel-wrap">
                    <div class="ud3-carousel-track" id="salesSlider">
                        @foreach($latestSales as $sale)
                            @php
                                $img = $sale->images->sortBy('sort_order')->first();
                                $imgUrl = $img ? asset('storage/' . $img->path) : 'https://asset.hasznaltautocdn.com/skeletor/images/no-image.31cc7f70.svg';
                            @endphp
                            <a href="{{ route('sales.show', $sale) }}" class="ud3-slide">
                                <div class="ud3-slide-img">
                                    <img src="{{ $imgUrl }}" alt="{{ $sale->car?->make_model ?? 'Eladó autó' }}" loading="lazy">
                                </div>
                                <div class="ud3-slide-info">
                                    <strong>{{ $sale->car?->make_model ?? 'Ismeretlen' }}</strong>
                                    <span class="ud3-slide-price">{{ number_format($sale->price, 0, ',', ' ') }} Ft</span>
                                    @if($sale->mileage)
                                        <span class="ud3-slide-km">{{ number_format($sale->mileage, 0, ',', ' ') }} km</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="ud3-carousel-nav">
                        <button type="button" class="ud3-carousel-btn" id="salesPrev" aria-label="Előző">‹</button>
                        <button type="button" class="ud3-carousel-btn" id="salesNext" aria-label="Következő">›</button>
                    </div>
                </div>
            @else
                <p class="ud3-empty-text">Jelenleg nincs eladó autó.</p>
            @endif
        </section>

        <section class="ud3-notif-card fade-in delay-2">
            <div class="ud3-card-head">
                <h2>Értesítések</h2>
            </div>
            @if($adminNotifications->count() > 0)
                <div class="ud3-notif-list">
                    @foreach($adminNotifications as $notification)
                        <div class="ud3-notif-item">
                            <div class="ud3-notif-dot"></div>
                            <div class="ud3-notif-body">
                                <strong>{{ $notification->title }}</strong>
                                <span>{{ $notification->message }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="ud3-empty-text">Még nincs értesítésed.</p>
            @endif
        </section>
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('salesSlider');
    if (!slider) return;
    const prev = document.getElementById('salesPrev');
    const next = document.getElementById('salesNext');
    let current = 0;
    const slides = slider.querySelectorAll('.ud3-slide');
    const total = slides.length;
    if (total <= 1) { prev && (prev.style.display = 'none'); next && (next.style.display = 'none'); return; }

    function show(idx) {
        current = (idx + total) % total;
        slider.style.transform = 'translateX(-' + (current * 100) + '%)';
    }

    prev && prev.addEventListener('click', function () { show(current - 1); });
    next && next.addEventListener('click', function () { show(current + 1); });

    let timer = setInterval(function () { show(current + 1); }, 4000);
    slider.closest('.ud3-carousel-card').addEventListener('mouseenter', function () { clearInterval(timer); });
    slider.closest('.ud3-carousel-card').addEventListener('mouseleave', function () { timer = setInterval(function () { show(current + 1); }, 4000); });
});
</script>

@endsection
