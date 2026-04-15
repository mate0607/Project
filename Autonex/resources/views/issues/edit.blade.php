@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Hiba szerkesztése</h1>
        <p>Frissítsd a hibabejelentés adatait.</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('issues.update', $issue) }}">
            @csrf
            @method('PUT')

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label for="car_id">Autó</label>
                    <select id="car_id" name="car_id">
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ (string) old('car_id', $issue->car_id) === (string) $car->id ? 'selected' : '' }}>
                                #{{ $car->id }} - {{ $car->make_model }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="category">Kategória</label>
                    <input id="category" type="text" name="category" value="{{ old('category', $issue->category) }}">
                    @error('category') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="urgency">Sürgősség</label>
                    <select id="urgency" name="urgency">
                        <option value="low" {{ old('urgency', $issue->urgency) === 'low' ? 'selected' : '' }}>Alacsony</option>
                        <option value="medium" {{ old('urgency', $issue->urgency) === 'medium' ? 'selected' : '' }}>Közepes</option>
                        <option value="high" {{ old('urgency', $issue->urgency) === 'high' ? 'selected' : '' }}>Magas</option>
                    </select>
                    @error('urgency') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="description">Leírás</label>
                    <textarea id="description" name="description" rows="5">{{ old('description', $issue->description) }}</textarea>
                    @error('description') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Frissítés</button>
                <a href="{{ route('issues.show', $issue) }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection