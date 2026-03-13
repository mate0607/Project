@extends('layouts.app')

@section('content')

<section class="sales-form-head">
    <h1 class="page-title">Új eladás rögzítése</h1>
    <p class="page-subtitle">Töltsd ki az adatokat, és publikáld az ajánlatot.</p>
</section>

<div class="card sale-form-card">
    <form method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
            <div style="background:#3b1111;border:1px solid #e74c3c;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#f87171;">
                <strong>Hiba:</strong>
                <ul style="margin:6px 0 0 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="sales-form-grid">
            <div>
                <label for="car_id">Autó</label>
                <select id="car_id" name="car_id" class="sale-select">
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
            </div>

            <div>
                <label for="buyer_id">Vevő</label>
                <select id="buyer_id" name="buyer_id" class="sale-select">
                    <option value="">Válassz felhasználót...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) old('buyer_id') === (string) $user->id ? 'selected' : '' }}>
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
                <input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="pl. 1250000">
                @error('price')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mileage">Kilométer</label>
                <input id="mileage" type="number" name="mileage" value="{{ old('mileage') }}" placeholder="pl. 98000">
                @error('mileage')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="car_condition">Állapot</label>
                <input id="car_condition" type="text" name="car_condition" value="{{ old('car_condition') }}" placeholder="pl. Good">
                @error('car_condition')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active">Státusz</label>
                <select id="is_active" name="is_active" class="sale-select">
                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Aktív</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inaktív</option>
                </select>
                @error('is_active')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <label for="images">Képek az autóról (max 10)</label>
        <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
        @error('images')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @error('images.*')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Leírás</label>
        <textarea id="description" name="description" rows="5" class="sale-textarea" placeholder="Részletezd az autó állapotát, extráit...">{{ old('description') }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn sale-btn-main">Mentés</button>
            <a href="{{ route('sales.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection
