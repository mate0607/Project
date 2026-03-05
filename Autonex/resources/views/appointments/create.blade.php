@extends('layouts.app')

@section('content')

<h1 class="page-title">Új időpont</h1>

<div class="card app-form-card">
    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <label for="car_id">Autó</label>
        <select id="car_id" name="car_id" class="app-select">
            <option value="">Válassz autót...</option>
            @foreach($cars as $car)
                <option value="{{ $car->id }}" {{ (string) old('car_id') === (string) $car->id ? 'selected' : '' }}>
                    #{{ $car->id }} - {{ $car->make_model }}
                </option>
            @endforeach
        </select>
        @error('car_id')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="date">Dátum</label>
        <input id="date" type="date" name="date" value="{{ old('date') }}">
        @error('date')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="time">Időpont</label>
        <input id="time" type="time" name="time" value="{{ old('time') }}">
        @error('time')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="service">Szerviz típusa</label>
        <input id="service" type="text" name="service" value="{{ old('service') }}" placeholder="Pl.: olajcsere, fékellenőrzés (opcionális)">
        @error('service')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Megjegyzés</label>
        <textarea id="description" name="description" rows="4" class="app-textarea" placeholder="Írj megjegyzést az időponthoz (opcionális)">{{ old('description') }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn app-btn-main">Mentés</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection