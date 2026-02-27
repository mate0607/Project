@extends('layouts.app')

@section('content')

<section class="hero">
    <div>
        <h1 class="page-title">Felhasználói Dashboard</h1>
        <p class="page-subtitle">Saját időpontjaid és foglalásaid áttekintése.</p>
    </div>

    <a href="{{ route('appointments.create') }}" class="btn app-btn-main">+ Új időpont foglalása</a>
</section>

<div class="card app-card">
    <h3 style="margin-bottom: 12px;">Saját időpontok</h3>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>
                            <span class="app-status app-status-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn-small app-btn">Megnyit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Még nincs időpontod.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
