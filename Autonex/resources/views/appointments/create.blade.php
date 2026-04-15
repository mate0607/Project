@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új időpont</h1>
        <p>Add meg az autót és az időpont részleteit.</p>
    </div>

    @if($cars->isEmpty())
        <div class="anx-form-card anx-form-card--md" style="text-align:center;">
            <p class="anx-info-text" style="margin-bottom:18px;">Időpont foglaláshoz először adj hozzá egy autót.</p>
            <a href="{{ route('cars.create') }}" class="anx-btn-primary">Autó hozzáadása</a>
        </div>
    @else
        <div class="anx-form-card anx-form-card--md">
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="anx-grid anx-grid--2">
                    <div class="anx-field anx-field--full">
                        <label for="car_id">Autó</label>
                        <select id="car_id" name="car_id">
                            <option value="">Válassz autót...</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}" {{ (string) old('car_id') === (string) $car->id ? 'selected' : '' }}>
                                    #{{ $car->id }} - {{ $car->make_model }}
                                </option>
                            @endforeach
                        </select>
                        @error('car_id') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="anx-field">
                        <label for="date">Dátum</label>
                        <input id="date" type="date" name="date" value="{{ old('date') }}">
                        @error('date') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="anx-field">
                        <label for="time">Időpont</label>
                        <input id="time" type="time" name="time" value="{{ old('time') }}">
                        @error('time') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="anx-field anx-field--full">
                        <label for="service">Szerviz típusa</label>
                        <input id="service" type="text" name="service" value="{{ old('service') }}" placeholder="Pl.: olajcsere, fékellenőrzés (opcionális)">
                        @error('service') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="anx-field anx-field--full">
                        <label for="description">Megjegyzés</label>
                        <textarea id="description" name="description" rows="4" placeholder="Írj megjegyzést az időponthoz (opcionális)">{{ old('description') }}</textarea>
                        @error('description') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="anx-actions">
                    <button type="submit" class="anx-btn-primary">Mentés</button>
                    <a href="{{ route('appointments.index') }}" class="anx-btn-secondary">Mégse</a>
                </div>
            </form>
        </div>
    @endif
</section>
@endsection