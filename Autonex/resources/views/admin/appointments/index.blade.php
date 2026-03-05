@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Időpont kezelés</h1>
</div>

@if($errors->any())
    <div class="card" style="margin-top:16px; border-color: rgba(248, 113, 113, 0.45);">
        <p style="color:#fecaca;">{{ $errors->first() }}</p>
    </div>
@endif

<div class="cards-grid" style="margin-top: 16px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
    <div class="stat-card">
        <span class="stat-label">Pending időpontok</span>
        <div class="stat-value">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-label">Confirmed időpontok</span>
        <div class="stat-value">{{ $confirmedCount }}</div>
    </div>
</div>

<div class="card app-card" style="margin-top:20px;">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Felhasználó</th>
                    <th>Autó</th>
                    <th>Dátum</th>
                    <th>Idő</th>
                    <th>Státusz</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->user?->name ?? '—' }}</td>
                        <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>
                            <span class="app-status app-status-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn-small app-btn">Szerkesztés</a>

                            <form method="POST" action="{{ route('admin.appointments.update-status', $appointment) }}" class="inline-form" style="display:flex; gap:8px; align-items:center;">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="app-select" style="min-width: 150px; padding: 8px 10px;">
                                    <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>confirmed</option>
                                    <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                    <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>completed</option>
                                </select>
                                <button type="submit" class="btn-small app-btn">Mentés</button>
                            </form>
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
