@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új időpont</h1>
        <p>Add meg az ügyfél és az autó adatait, majd az időpont részleteit.</p>
    </div>

    <div class="anx-form-card anx-form-card--lg">
        <form method="POST" action="{{ route('admin.appointments.store') }}">
            @csrf

            <div class="anx-grid anx-grid--2">
                {{-- Ügyfél adatok --}}
                <div class="anx-field">
                    <label for="customer_name">Ügyfél neve</label>
                    <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Pl.: Kiss János">
                    @error('customer_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="customer_phone">Telefonszám</label>
                    <input id="customer_phone" type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Pl.: +36 30 123 4567">
                    @error('customer_phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Autó adatok --}}
                <div class="anx-field">
                    <label for="car_brand">Márka</label>
                    <input id="car_brand" type="text" name="car_brand" value="{{ old('car_brand') }}" placeholder="Pl.: Toyota">
                    @error('car_brand') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="car_model">Modell</label>
                    <input id="car_model" type="text" name="car_model" value="{{ old('car_model') }}" placeholder="Pl.: Corolla">
                    @error('car_model') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="car_year">Évjárat</label>
                    <input id="car_year" type="number" name="car_year" value="{{ old('car_year') }}" placeholder="Pl.: 2020" min="1900" max="{{ date('Y') + 1 }}">
                    @error('car_year') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="car_engine">Motor</label>
                    <input id="car_engine" type="text" name="car_engine" value="{{ old('car_engine') }}" placeholder="Pl.: 1.6 VVT-i">
                    @error('car_engine') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="car_fuel_type">Üzemanyag</label>
                    <input id="car_fuel_type" type="text" name="car_fuel_type" value="{{ old('car_fuel_type') }}" placeholder="Pl.: Benzin">
                    @error('car_fuel_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Időpont adatok --}}
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
                <a href="{{ route('admin.appointments.index') }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection
