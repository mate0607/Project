@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Időpont adatai</h1>
    <a href="{{ route('appointments.index') }}" class="btn btn-muted">Vissza</a>
</div>

<div class="card detail-card app-detail-card">
    <div class="detail-row">
        <span class="detail-label">ID</span>
        <span class="detail-value">{{ $appointment->id }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Ügyfél</span>
        <span class="detail-value">{{ $appointment->user?->name ?? '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Autó</span>
        <span class="detail-value">{{ $appointment->car?->make_model ?? '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Dátum</span>
        <span class="detail-value">{{ $appointment->date }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Idő</span>
        <span class="detail-value">{{ $appointment->time }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Szolgáltatás</span>
        <span class="detail-value">{{ $appointment->service }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Státusz</span>
        <span class="detail-value"><span class="app-status app-status-{{ $appointment->status }}">{{ strtoupper($appointment->status) }}</span></span>
    </div>

</div>

@endsection