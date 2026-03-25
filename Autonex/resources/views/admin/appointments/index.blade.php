@extends('layouts.app')

@section('content')

@php
    $stageLabels = [
        'received' => 'Átvéve',
        'inspected' => 'Átvizsgálva',
        'in_progress' => 'Szerelés alatt',
        'ready' => 'Kész, elvihető',
    ];
@endphp

<div class="page-head" style="display:flex;justify-content:space-between;align-items:center;">
    <h1 class="page-title">Időpont kezelés</h1>
    <div style="display:flex;gap:8px;align-items:center;">
        <button type="button" id="searchToggleBtn" title="Keresés" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,0.18);border:1px solid rgba(96,165,250,0.35);cursor:pointer;transition:background 0.2s;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        </button>
        <a href="{{ route('appointments.create') }}" class="btn sale-btn-main">+ Új időpont</a>
    </div>
</div>

<div id="searchPanel" style="display:none;margin-top:12px;">
    <div class="card app-card" style="padding:16px;">
        <form method="GET" action="{{ route('admin.appointments.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="filter_name" placeholder="Név keresése..." value="{{ request('filter_name') }}" class="admin-filter-input" style="flex:1;min-width:160px;">
            <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="admin-filter-input" style="min-width:160px;">
            <button type="submit" class="btn sale-btn-main">Keresés</button>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-muted">Törlés</a>
        </form>
    </div>
</div>

<script>
(function() {
    var btn = document.getElementById('searchToggleBtn');
    var panel = document.getElementById('searchPanel');
    btn.addEventListener('click', function() {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    @if(request('filter_name') || request('filter_date'))
        panel.style.display = 'block';
    @endif
})();
</script>

<div class="card app-card" style="margin-top:20px;">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Felhasználó</th>
                    <th>Autó</th>
                    <th>Dátum</th>
                    <th>Idő</th>
                    <th>Szerviz állapot</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->user?->name ?? '—' }}</td>
                        <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $appointment->date instanceof \Carbon\Carbon ? $appointment->date->format('Y-m-d') : $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>
                            @if($appointment->service_stage)
                                <span class="app-status" style="background: rgba(59,130,246,0.18); color: #93c5fd; border-color: rgba(59,130,246,0.4);">
                                    {{ $stageLabels[$appointment->service_stage] ?? $appointment->service_stage }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.appointments.edit', $appointment) }}" title="Szerkesztés" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:rgba(59,130,246,0.18);border:1px solid rgba(96,165,250,0.35);transition:background 0.2s;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Nincs még rögzített időpont.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
