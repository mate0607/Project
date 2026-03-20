@extends('layouts.app')

@section('content')

<section class="cars-hero cars-hero-tight">
	<p class="cars-kicker">Create Car</p>
	<h1 class="page-title">Új autó</h1>
</section>

<div class="cars-split-layout">
    <aside class="card cars-side-panel">
        <h3>Tippek</h3>
        <p>A típus mezőbe írd be együtt a márkát és modellt.</p>
        <p>A VIN opcionális, de későbbi azonosításhoz hasznos.</p>
    </aside>

    <div class="card form-card car-form-card">
	<form method="POST" action="{{ route('cars.store') }}">
		@csrf

		<label for="make_model">Típus (márka + modell)</label>
		<input id="make_model" type="text" name="make_model" value="{{ old('make_model') }}" placeholder="pl. Toyota Corolla">
		@error('make_model')
			<p class="field-error">{{ $message }}</p>
		@enderror

		<label for="vin">VIN</label>
		<input id="vin" type="text" name="vin" value="{{ old('vin') }}" placeholder="pl. 1HGCM82633A123456">
		@error('vin')
			<p class="field-error">{{ $message }}</p>
		@enderror

		<label for="license_plate">Rendszám</label>
		<input id="license_plate" type="text" name="license_plate" value="{{ old('license_plate') }}" placeholder="pl. ABC-123">
		@error('license_plate')
			<p class="field-error">{{ $message }}</p>
		@enderror

		<label for="year">Év</label>
		<input id="year" type="number" name="year" value="{{ old('year') }}" placeholder="pl. 2020">
		@error('year')
			<p class="field-error">{{ $message }}</p>
		@enderror

		<div class="form-actions">
			<button class="btn car-btn-main" type="submit">Mentés</button>
			<a href="{{ route('cars.index') }}" class="btn btn-muted">Mégse</a>
		</div>
	</form>
    </div>
</div>

@endsection