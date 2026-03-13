@extends('layouts.app')

@section('content')

<h1 class="page-title">Új hiba</h1>

<div class="card issue-form-card">
    <form method="POST" action="{{ route('issues.store') }}">
        @csrf

        <label for="car_id">Autó</label>
        <select id="car_id" name="car_id" class="issue-select">
            <option value="">Válassz autót...</option>
            @foreach($cars as $car)
                <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                    #{{ $car->id }} - {{ $car->make_model }}
                </option>
            @endforeach
        </select>
        @error('car_id')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="category">Kategória</label>
        <input id="category" type="text" name="category" value="{{ old('category') }}" placeholder="pl. Engine, Brakes">
        @error('category')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Leírás</label>
        <textarea id="description" name="description" rows="5" class="issue-textarea" placeholder="Írd le részletesen a hibát...">{{ old('description') }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="urgency">Sürgősség</label>
        <select id="urgency" name="urgency" class="issue-select">
            <option value="low" {{ old('urgency') === 'low' ? 'selected' : '' }}>Alacsony</option>
            <option value="medium" {{ old('urgency', 'medium') === 'medium' ? 'selected' : '' }}>Közepes</option>
            <option value="high" {{ old('urgency') === 'high' ? 'selected' : '' }}>Magas</option>
        </select>
        @error('urgency')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn issue-btn-main">Mentés</button>
            <a href="{{ route('issues.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection