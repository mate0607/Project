@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Időpont szerkesztése (Admin)</h1>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-muted">Vissza</a>
</div>

<div class="card app-form-card" style="margin-top: 16px;">
    <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
        @csrf
        @method('PUT')

        <label>Felhasználó</label>
        <input type="text" value="{{ $appointment->user?->name ?? '—' }}" disabled>

        <label for="car_id">Autó</label>
        <select id="car_id" name="car_id" class="app-select">
            @foreach($cars as $car)
                <option value="{{ $car->id }}" {{ (string) old('car_id', $appointment->car_id) === (string) $car->id ? 'selected' : '' }}>
                    #{{ $car->id }} - {{ $car->make_model }}
                </option>
            @endforeach
        </select>
        @error('car_id')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="date">Dátum</label>
        <input id="date" type="date" name="date" value="{{ old('date', $appointment->date) }}">
        @error('date')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="time">Időpont</label>
        <input id="time" type="time" name="time" value="{{ old('time', \Illuminate\Support\Carbon::parse($appointment->time)->format('H:i')) }}">
        @error('time')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Megjegyzés</label>
        <textarea id="description" name="description" rows="4" placeholder="Megjegyzés (opcionális)">{{ old('description', $appointment->description) }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="status">Státusz</label>
        <select id="status" name="status" class="app-select">
            @foreach(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ old('status', $appointment->status) === $status ? 'selected' : '' }}>
                    {{ strtoupper($status) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn app-btn-main">Mentés</button>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection
