@extends('layouts.app')

@section('content')

<h1 class="page-title">Időpont szerkesztése</h1>

<div class="card app-form-card">
    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
        @csrf
        @method('PUT')

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

        <label for="service">Szolgáltatás</label>
        <input id="service" type="text" name="service" value="{{ old('service', $appointment->service) }}">
        @error('service')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="status">Státusz</label>
        <select id="status" name="status" class="app-select">
            <option value="pending" {{ old('status', $appointment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        @error('status')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn app-btn-main">Frissítés</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection