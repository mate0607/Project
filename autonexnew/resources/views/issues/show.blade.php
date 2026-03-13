@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Hiba adatai</h1>
    <a href="{{ route('issues.index') }}" class="btn btn-muted">Vissza</a>
</div>

<div class="card detail-card issue-detail-card">
    <div class="detail-row">
        <span class="detail-label">ID</span>
        <span class="detail-value">{{ $issue->id }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Autó</span>
        <span class="detail-value">{{ $issue->car?->make_model ?? 'Nincs hozzárendelve' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Kategória</span>
        <span class="detail-value">{{ $issue->category }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Sürgősség</span>
        <span class="detail-value"><span class="urgency urgency-{{ $issue->urgency }}">{{ strtoupper($issue->urgency) }}</span></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Leírás</span>
        <span class="detail-value">{{ $issue->description }}</span>
    </div>

    <div class="form-actions" style="margin-top: 18px;">
        <a href="{{ route('issues.edit', $issue) }}" class="btn issue-btn-main">Szerkesztés</a>
    </div>
</div>

@endsection