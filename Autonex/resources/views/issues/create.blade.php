@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új hiba</h1>
        <p>Jelentsd be a járműved hibáját.</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('issues.store') }}">
            @csrf

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label for="car_id">Autó</label>
                    <select id="car_id" name="car_id">
                        <option value="">Válassz autót...</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                #{{ $car->id }} - {{ $car->make_model }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="category">Kategória</label>
                    <input id="category" type="text" name="category" value="{{ old('category') }}" placeholder="pl. Motor, Fékek">
                    @error('category') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="urgency">Sürgősség</label>
                    <select id="urgency" name="urgency">
                        <option value="low" {{ old('urgency') === 'low' ? 'selected' : '' }}>Alacsony</option>
                        <option value="medium" {{ old('urgency', 'medium') === 'medium' ? 'selected' : '' }}>Közepes</option>
                        <option value="high" {{ old('urgency') === 'high' ? 'selected' : '' }}>Magas</option>
                    </select>
                    @error('urgency') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="description">Leírás</label>
                    <textarea id="description" name="description" rows="5" placeholder="Írd le részletesen a hibát...">{{ old('description') }}</textarea>
                    @error('description') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Mentés</button>
                <a href="{{ route('issues.index') }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection