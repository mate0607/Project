@extends('layouts.app')

@section('content')

<h1 class="page-title">Hiba szerkesztése</h1>

<div class="card issue-form-card">
    <form method="POST" action="{{ route('issues.update', $issue) }}">
        @csrf
        @method('PUT')

        <label for="car_id">Autó</label>
        <select id="car_id" name="car_id" class="issue-select">
            @foreach($cars as $car)
                <option value="{{ $car->id }}" {{ (string) old('car_id', $issue->car_id) === (string) $car->id ? 'selected' : '' }}>
                    #{{ $car->id }} - {{ $car->make_model }}
                </option>
            @endforeach
        </select>
        @error('car_id')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="category">Kategória</label>
        <input id="category" type="text" name="category" value="{{ old('category', $issue->category) }}">
        @error('category')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Leírás</label>
        <textarea id="description" name="description" rows="5" class="issue-textarea">{{ old('description', $issue->description) }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="urgency">Sürgősség</label>
        <select id="urgency" name="urgency" class="issue-select">
            <option value="low" {{ old('urgency', $issue->urgency) === 'low' ? 'selected' : '' }}>Alacsony</option>
            <option value="medium" {{ old('urgency', $issue->urgency) === 'medium' ? 'selected' : '' }}>Közepes</option>
            <option value="high" {{ old('urgency', $issue->urgency) === 'high' ? 'selected' : '' }}>Magas</option>
        </select>
        @error('urgency')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="form-actions">
            <button type="submit" class="btn issue-btn-main">Frissítés</button>
            <a href="{{ route('issues.show', $issue) }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

@endsection