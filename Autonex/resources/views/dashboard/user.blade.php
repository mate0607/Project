@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@section('content')

<section class="user-dashboard">

    {{-- Eladó autók carousel --}}
    <section class="ud-sales-carousel fade-in">
        <div class="ud-sales-head">
            <h2>Legújabb eladó autók</h2>
        </div>
        @if($latestSales->count() > 0)
            <div class="ud-sales-slider" id="salesSlider">
                @foreach($latestSales as $sale)
                    @php
                        $img = $sale->images->sortBy('sort_order')->first();
                        $imgUrl = $img ? asset('storage/' . $img->path) : 'https://asset.hasznaltautocdn.com/skeletor/images/no-image.31cc7f70.svg';
                    @endphp
                    <a href="{{ route('sales.show', $sale) }}" class="ud-sale-slide">
                        <div class="ud-sale-img-wrap">
                            <img src="{{ $imgUrl }}" alt="{{ $sale->car?->make_model ?? 'Eladó autó' }}" loading="lazy">
                        </div>
                        <div class="ud-sale-info">
                            <strong>{{ $sale->car?->make_model ?? 'Ismeretlen' }}</strong>
                            <span class="ud-sale-price">{{ number_format($sale->price, 0, ',', ' ') }} Ft</span>
                            @if($sale->mileage)
                                <span class="ud-sale-km">{{ number_format($sale->mileage, 0, ',', ' ') }} km</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="ud-sales-nav">
                <button type="button" class="ud-sales-btn" id="salesPrev" aria-label="Előző">‹</button>
                <button type="button" class="ud-sales-btn" id="salesNext" aria-label="Következő">›</button>
            </div>
        @else
            <p class="ud-empty">Jelenleg nincs eladó autó.</p>
        @endif
    </section>

    {{-- Stat kártyák: Szervizben + Közelgő + Új időpont --}}
    <section class="ud-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
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

        <a href="{{ route('appointments.create') }}" class="ud-stat-card ud-stat-card-action fade-in delay-3">
            <div class="ud-stat-icon ud-stat-icon-action" aria-hidden="true">
                <svg class="ud-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </div>
            <div class="ud-stat-label-action">Új időpont foglalás</div>
        </a>
    </section>

    {{-- Következő időpont --}}
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

    {{-- Értesítések --}}
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

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('salesSlider');
    if (!slider) return;
    const prev = document.getElementById('salesPrev');
    const next = document.getElementById('salesNext');
    let current = 0;
    const slides = slider.querySelectorAll('.ud-sale-slide');
    const total = slides.length;
    if (total <= 1) { prev && (prev.style.display = 'none'); next && (next.style.display = 'none'); return; }

    function show(idx) {
        current = (idx + total) % total;
        slider.style.transform = 'translateX(-' + (current * 100) + '%)';
    }

    prev && prev.addEventListener('click', function () { show(current - 1); });
    next && next.addEventListener('click', function () { show(current + 1); });

    // Auto-rotate every 4 seconds
    let timer = setInterval(function () { show(current + 1); }, 4000);
    slider.closest('.ud-sales-carousel').addEventListener('mouseenter', function () { clearInterval(timer); });
    slider.closest('.ud-sales-carousel').addEventListener('mouseleave', function () { timer = setInterval(function () { show(current + 1); }, 4000); });
});
</script>

@endsection
