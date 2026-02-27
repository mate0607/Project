@extends('layouts.app')

@section('content')

<section class="sales-form-head">
    <h1 class="page-title">Eladás szerkesztése</h1>
    <p class="page-subtitle">Frissítsd az ajánlat adatait.</p>
</section>

<div class="card sale-form-card">
    <form method="POST" action="{{ route('sales.update', $sale) }}">
        @csrf
        @method('PUT')

        <div class="sales-form-grid">
            <div>
                <label for="car_id">Autó</label>
                <select id="car_id" name="car_id" class="sale-select">
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ (string) old('car_id', $sale->car_id) === (string) $car->id ? 'selected' : '' }}>
                            #{{ $car->id }} - {{ $car->make_model }}
                        </option>
                    @endforeach
                </select>
                @error('car_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="buyer_id">Vevő</label>
                <select id="buyer_id" name="buyer_id" class="sale-select">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) old('buyer_id', $sale->buyer_id) === (string) $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('buyer_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price">Ár (Ft)</label>
                <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $sale->price) }}">
                @error('price')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mileage">Kilométer</label>
                <input id="mileage" type="number" name="mileage" value="{{ old('mileage', $sale->mileage) }}">
                @error('mileage')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="car_condition">Állapot</label>
                <input id="car_condition" type="text" name="car_condition" value="{{ old('car_condition', $sale->car_condition) }}">
                @error('car_condition')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active">Státusz</label>
                <select id="is_active" name="is_active" class="sale-select">
                    <option value="1" {{ (string) old('is_active', (int) $sale->is_active) === '1' ? 'selected' : '' }}>Aktív</option>
                    <option value="0" {{ (string) old('is_active', (int) $sale->is_active) === '0' ? 'selected' : '' }}>Inaktív</option>
                </select>
                @error('is_active')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <label for="description">Leírás</label>
        <textarea id="description" name="description" rows="5" class="sale-textarea">{{ old('description', $sale->description) }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn sale-btn-main">Frissítés</button>
            <a href="{{ route('sales.show', $sale) }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection
