@extends('layouts.app')


@section('content')

@php
    $now = now();

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

    $stages = ['received', 'inspected', 'in_progress', 'ready'];

    $appointments = $car->appointments()->with('servicePhotos')->latest()->get();

    $activeService = $appointments->filter(fn ($a) => in_array($a->status, ['in_progress', 'confirmed']) && $a->service_stage)->first();

    $upcomingAppointments = $appointments->filter(function ($a) use ($now) {
        $datePart = $a->date instanceof \Carbon\CarbonInterface
            ? $a->date->toDateString()
            : \Carbon\Carbon::parse((string) $a->date)->toDateString();
        $dt = \Carbon\Carbon::parse($datePart . ' ' . \Carbon\Carbon::parse((string) $a->time)->format('H:i:s'));
        return $dt->greaterThanOrEqualTo($now) && in_array($a->status, ['pending', 'confirmed']);
    })->sortBy(fn ($a) => $a->date);

    $pastServices = $appointments->where('status', 'completed')->sortByDesc('date');
@endphp

<section class="cars-shell cars-page-enter">
    {{-- Vissza nyíl --}}
    <a href="{{ route('cars.index') }}" class="car-back-arrow" title="Vissza a listára">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5"/>
            <path d="M12 19l-7-7 7-7"/>
        </svg>
    </a>

    {{-- Fejléc --}}
    <article class="car-profile-hero">
        <div class="car-profile-main">
            <span class="car-detail-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 16H9m10 0h2m-1-4 1 4-1 4h-1M3 16h2m0 0h4m-4 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2Zm14 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2ZM5 16l1.3-5.1A2 2 0 0 1 8.24 9.4h6.52a2 2 0 0 1 1.94 1.5L18 16"></path>
                </svg>
            </span>

            <div>
                <h1 class="page-title car-detail-title">{{ $car->make_model }}</h1>
                <div class="car-meta-chips">
                    <span>Rendszám: {{ $car->license_plate ?? 'Nincs megadva' }}</span>
                    <span>Év: {{ $car->year ?? 'Nincs megadva' }}</span>
                    <span>ID: #{{ $car->id }}</span>
                </div>
            </div>
        </div>

        <div class="car-profile-actions">
            <a href="{{ route('cars.edit', $car) }}" class="car-edit-icon" title="Szerkesztés">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
        </div>
    </article>

    {{-- Aktív szerviz állapot --}}
    @if($activeService)
        <div class="svc-progress-wrap">
            <h2 class="svc-section-title">Jelenlegi szerviz állapot</h2>
            @php
                $currentStageIndex = array_search($activeService->service_stage, $stages);
            @endphp
            <div class="svc-progress-bar">
                @foreach($stages as $i => $stage)
                    @php
                        $done = $i <= $currentStageIndex;
                        $active = $i === $currentStageIndex;
                    @endphp
                    <div class="svc-step {{ $done ? 'svc-step-done' : '' }} {{ $active ? 'svc-step-active' : '' }}">
                        <div class="svc-step-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                @if($stage === 'received')
                                    <path d="M9 17H5a2 2 0 0 0-2 2m4-2a2 2 0 1 1-4 0m4 0h6m6 0a2 2 0 0 1-2 2m2-2a2 2 0 1 0-4 0m4 0H15m0 0a2 2 0 1 0-4 0m-1-4 1.3-5.1A2 2 0 0 1 13.24 9.4h0"/>
                                @elseif($stage === 'inspected')
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                @elseif($stage === 'in_progress')
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                @else
                                    <path d="M20 6 9 17l-5-5"/>
                                @endif
                            </svg>
                        </div>
                        <div class="svc-step-label">{{ $stageLabels[$stage] }}</div>
                        <div class="svc-step-num">{{ $i + 1 }}.</div>
                    </div>
                    @if($i < count($stages) - 1)
                        <div class="svc-step-line {{ $i < $currentStageIndex ? 'svc-step-line-done' : '' }}"></div>
                    @endif
                @endforeach
            </div>
            <a href="{{ route('appointments.show', $activeService) }}" class="svc-link-detail">Részletek megtekintése →</a>
        </div>
    @endif

    {{-- Közelgő időpontok --}}
    @if($upcomingAppointments->count() > 0)
        <div class="car-section-wrap">
            <h2 class="svc-section-title">Közelgő időpontok</h2>
            <div class="car-appointments-list">
                @foreach($upcomingAppointments as $apt)
                    @php
                        $aptDate = $apt->date instanceof \Carbon\CarbonInterface
                            ? $apt->date->format('Y.m.d')
                            : \Carbon\Carbon::parse((string) $apt->date)->format('Y.m.d');
                        $aptTime = \Carbon\Carbon::parse((string) $apt->time)->format('H:i');
                    @endphp
                    <a href="{{ route('appointments.show', $apt) }}" class="car-apt-card">
                        <div>
                            <strong>{{ $apt->service ?: 'Általános szerviz' }}</strong>
                            <span class="car-apt-date">{{ $aptDate }} – {{ $aptTime }}</span>
                        </div>
                        <span class="svc-badge svc-badge-{{ $apt->status }}">{{ $statusLabels[$apt->status] ?? $apt->status }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Korábbi szervizek --}}
    <div class="car-section-wrap">
        <h2 class="svc-section-title">Szerviz előzmények</h2>
        @if($pastServices->count() > 0)
            <div class="car-appointments-list">
                @foreach($pastServices as $past)
                    @php
                        $pDate = $past->date instanceof \Carbon\CarbonInterface
                            ? $past->date->format('Y.m.d')
                            : \Carbon\Carbon::parse((string) $past->date)->format('Y.m.d');
                    @endphp
                    <a href="{{ route('appointments.show', $past) }}" class="car-apt-card car-apt-completed">
                        <div>
                            <strong>{{ $past->service ?: 'Általános szerviz' }}</strong>
                            <span class="car-apt-date">{{ $pDate }}</span>
                            @if($past->total_cost)
                                <span class="car-apt-cost">{{ number_format($past->total_cost, 0, ',', ' ') }} Ft</span>
                            @endif
                        </div>
                        <span class="svc-badge svc-badge-completed">{{ $statusLabels['completed'] }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="car-empty-section">
                <p>Nincs korábbi szerviz.</p>
            </div>
        @endif
    </div>

    {{-- Szerviz fotók --}}
    @php
        $allPhotos = $appointments->flatMap(fn ($a) => $a->servicePhotos);
    @endphp
    @if($allPhotos->count() > 0)
        <div class="car-section-wrap">
            <h2 class="svc-section-title">Szerviz fotók</h2>
            <div class="svc-photos-grid">
                @foreach($allPhotos as $photo)
                    <div class="svc-photo-card">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">
                        <span>{{ $photo->title }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Üzenetek az autóhoz --}}
    <div class="car-section-wrap">
        <h2 class="svc-section-title" style="display:flex;align-items:center;gap:10px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Üzenetek
            <span id="carMsgBadge" style="display:none;background:linear-gradient(135deg,#5588ff,#3d6be6);color:#fff;font-size:12px;font-weight:700;padding:2px 8px;border-radius:10px;"></span>
        </h2>
        <div class="car-msg-box" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:14px;overflow:hidden;">
            <div id="carMsgThread" style="max-height:360px;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;">
                <p style="text-align:center;opacity:.4;padding:20px 0;" id="carMsgEmpty">Betöltés...</p>
            </div>
            <form method="POST" action="{{ route('cars.messages.store', $car) }}" style="border-top:1px solid rgba(255,255,255,.06);padding:12px 16px;display:flex;gap:10px;">
                @csrf
                <input type="text" name="message" placeholder="Írj üzenetet a szerviznek..." required maxlength="2000"
                    style="flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:inherit;font-size:14px;">
                <button type="submit" class="btn car-btn-main car-btn-themed" style="white-space:nowrap;padding:10px 20px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Küldés
                </button>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var thread = document.getElementById('carMsgThread');
    var badge = document.getElementById('carMsgBadge');
    var empty = document.getElementById('carMsgEmpty');

    function loadMessages() {
        fetch('{{ route('cars.messages.index', $car) }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (msgs) {
            thread.innerHTML = '';
            if (msgs.length === 0) {
                thread.innerHTML = '<p style="text-align:center;opacity:.4;padding:20px 0;">Még nincs üzenet. Írj a szerviznek!</p>';
                return;
            }
            msgs.forEach(function (m) {
                var align = m.is_mine ? 'flex-end' : 'flex-start';
                var bg = m.is_mine ? 'rgba(59,130,246,.15)' : 'rgba(255,255,255,.05)';
                var border = m.is_mine ? 'rgba(59,130,246,.25)' : 'rgba(255,255,255,.08)';
                var html = '<div style="display:flex;flex-direction:column;align-items:' + align + ';max-width:80%;align-self:' + align + ';">'
                    + '<div style="background:' + bg + ';border:1px solid ' + border + ';border-radius:12px;padding:10px 14px;word-break:break-word;">'
                    + '<small style="opacity:.5;font-size:.75rem;">' + m.sender_name + '</small>'
                    + '<p style="margin:4px 0 0;">' + m.message.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</p>'
                    + '</div>'
                    + '<small style="opacity:.35;font-size:.7rem;margin-top:2px;">' + m.created_at + '</small>'
                    + '</div>';
                thread.insertAdjacentHTML('beforeend', html);
            });
            thread.scrollTop = thread.scrollHeight;
        });
    }

    loadMessages();
});
</script>

@endsection