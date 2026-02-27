@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Időpontok</h1>
    <a href="{{ route('appointments.create') }}" class="btn app-btn-main">+ Új időpont</a>
</div>

<div class="card app-card" style="margin-top:20px;">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->car?->make_model ?? '—' }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>
                            <span class="app-status app-status-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn-small app-btn">Megnyit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Nincs még időpont rögzítve.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection