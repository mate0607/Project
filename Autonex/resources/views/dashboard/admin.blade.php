@extends('layouts.app')

@section('content')

@php
    $prevMonth = $calendarDate->copy()->subMonth()->format('Y-m');
    $nextMonth = $calendarDate->copy()->addMonth()->format('Y-m');
    $daysInMonth = $calendarDate->daysInMonth;
    $firstDayOfWeek = $calendarDate->copy()->startOfMonth()->dayOfWeekIso;
    $dayNames = ['H', 'K', 'Sze', 'Cs', 'P', 'Szo', 'V'];
@endphp

<section class="admin-dashboard">

    <section class="ad-stats-grid ad-stats-grid-3">
        <article class="ad-stat-card">
            <span class="ad-stat-label">Jelenleg szervizben</span>
            <strong class="ad-stat-value">{{ $inServiceCount }}</strong>
            <span class="ad-stat-sub">autó</span>
        </article>

        <article class="ad-stat-card ad-stat-clickable" onclick="document.getElementById('todayAppointmentsPanel').classList.toggle('ad-panel-open')">
            <span class="ad-stat-label">Mai időpontok</span>
            <strong class="ad-stat-value">{{ $todayAppointments->count() }}</strong>
            <span class="ad-stat-sub">Kattints a listáért ▾</span>
        </article>

        <article class="ad-stat-card ad-stat-clickable" onclick="document.getElementById('todayCompletedPanel').classList.toggle('ad-panel-open')">
            <span class="ad-stat-label">Mai kész autók</span>
            <strong class="ad-stat-value">{{ $todayCompletedCars->count() }}</strong>
            <span class="ad-stat-sub">Kattints a listáért ▾</span>
        </article>
    </section>

    <section id="todayAppointmentsPanel" class="ad-panel">
        <article class="ad-card">
            <h2>Mai időpontok</h2>
            @if($todayAppointments->count() > 0)
                <div class="ad-list">
                    @foreach($todayAppointments as $apt)
                        <a href="{{ route('admin.appointments.show', $apt) }}" class="ad-list-item">
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
                            </div>
                            <div class="ad-list-meta">
                                <span class="ad-badge ad-badge-completed">KÉSZ</span>
                                <a href="{{ route('admin.notifications.create') }}?user_id={{ $apt->user_id }}&title={{ urlencode('Szerviz kész — ' . ($apt->car?->make_model ?? '')) }}" class="ad-list-action-btn">Értesítés küldése →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="ad-empty">Nincs ma elkészült autó.</p>
            @endif
        </article>
    </section>

    {{-- 2 identical action cards --}}
    <section class="ad-grid-2col">
        <a href="{{ route('admin.appointments.create') }}" class="ad-action-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v4M10 16h4"/></svg>
            <span>Új időpont</span>
        </a>
        <a href="{{ route('admin.notifications.create') }}" class="ad-action-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span>Új értesítés</span>
        </a>
    </section>

    {{-- Calendar view --}}
    <section class="ad-calendar-box">
        <div class="ad-calendar-header">
            <a href="{{ route('admin.dashboard', ['month' => $prevMonth]) }}" class="ad-cal-nav">‹</a>
            <h2>{{ $calendarDate->isoFormat('YYYY MMMM') }}</h2>
            <a href="{{ route('admin.dashboard', ['month' => $nextMonth]) }}" class="ad-cal-nav">›</a>
        </div>

        <div class="ad-calendar-grid">
            @foreach($dayNames as $dn)
                <div class="ad-cal-dayname">{{ $dn }}</div>
            @endforeach

            @for($i = 1; $i < $firstDayOfWeek; $i++)
                <div class="ad-cal-empty"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateKey = $calendarDate->copy()->day($day)->format('Y-m-d');
                    $dayAppointments = $calendarAppointments->get($dateKey, collect());
                    $isToday = $dateKey === now()->format('Y-m-d');
                @endphp
                <div class="ad-cal-day {{ $isToday ? 'ad-cal-today' : '' }} {{ $dayAppointments->count() > 0 ? 'ad-cal-has-items' : '' }}"
                     onclick="toggleDayPanel('{{ $dateKey }}')"
                     data-date="{{ $dateKey }}">
                    <span class="ad-cal-day-num">{{ $day }}</span>
                    @if($dayAppointments->count() > 0)
                        <span class="ad-cal-dot">{{ $dayAppointments->count() }}</span>
                    @endif
                </div>
            @endfor
        </div>

        {{-- Day panels with half-hour timeline --}}
        @foreach($calendarAppointments as $dateKey => $dayAppts)
            @php
                $slots = [];
                foreach ($dayAppts as $apt) {
                    $t = \Carbon\Carbon::parse($apt->time);
                    $slotKey = $t->format('H') . ':' . ($t->minute < 30 ? '00' : '30');
                    $slots[$slotKey][] = $apt;
                }
                ksort($slots);

                $allSlots = [];
                if (count($slots) > 0) {
                    $minH = (int) min(array_map(fn($k) => explode(':', $k)[0], array_keys($slots)));
                    $maxH = (int) max(array_map(fn($k) => explode(':', $k)[0], array_keys($slots)));
                    for ($h = $minH; $h <= $maxH; $h++) {
                        $allSlots[] = sprintf('%02d:00', $h);
                        $allSlots[] = sprintf('%02d:30', $h);
                    }
                }
            @endphp
            <div class="ad-day-panel" id="day-{{ $dateKey }}" style="display:none;">
                <h3>{{ \Carbon\Carbon::parse($dateKey)->isoFormat('YYYY. MMMM D. (dddd)') }}</h3>
                <div class="ad-timeline">
                    @foreach($allSlots as $slot)
                        <div class="ad-timeline-slot {{ isset($slots[$slot]) ? 'ad-timeline-slot-active' : '' }}">
                            <span class="ad-timeline-time">{{ $slot }}</span>
                            <div class="ad-timeline-content">
                                @if(isset($slots[$slot]))
                                    @foreach($slots[$slot] as $apt)
                                        <a href="{{ route('admin.appointments.show', $apt) }}" class="ad-timeline-item">
                                            <strong>{{ $apt->car?->make_model ?? '—' }}</strong>
                                            <span>{{ $apt->user?->name ?? '—' }}</span>
                                            <span class="ad-badge ad-badge-{{ $apt->status }}">{{ strtoupper($apt->status) }}</span>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if(count($allSlots) === 0)
                        <p class="ad-empty">Nincs időpont ezen a napon.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </section>

</section>

<script>
    function toggleDayPanel(dateKey) {
        var panels = document.querySelectorAll('.ad-day-panel');
        panels.forEach(function(p) {
            if (p.id === 'day-' + dateKey) {
                p.style.display = p.style.display === 'none' ? 'block' : 'none';
            } else {
                p.style.display = 'none';
            }
        });
    }
</script>
@endsection
