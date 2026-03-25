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
                <label for="vehicle_type">Jármű típus</label>
                <select id="vehicle_type" name="vehicle_type" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Autó', 'Motor', 'Kis teherautó', 'Mezőgazdasági gép', 'Egyéb'] as $vt)
                        <option value="{{ $vt }}" {{ old('vehicle_type') === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                    @endforeach
                </select>
                @error('vehicle_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="model">Modell</label>
                <input id="model" type="text" name="model" value="{{ old('model') }}" placeholder="pl. Toyota Corolla">
                @error('model') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="body_type">Karosszéria</label>
                <select id="body_type" name="body_type" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Sedan', 'Kombi', 'Hatchback', 'SUV', 'Kupé', 'Kabrió', 'Furgon', 'Pickup', 'Egyéb'] as $bt)
                        <option value="{{ $bt }}" {{ old('body_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
                @error('body_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="engine_cc">Köbcenti (cm³)</label>
                <input id="engine_cc" type="number" name="engine_cc" value="{{ old('engine_cc') }}" placeholder="pl. 1600">
                @error('engine_cc') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="fuel_type">Üzemanyag típus</label>
                <select id="fuel_type" name="fuel_type" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Benzin', 'Dízel', 'Elektromos', 'Hibrid', 'LPG', 'CNG', 'Egyéb'] as $ft)
                        <option value="{{ $ft }}" {{ old('fuel_type') === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                    @endforeach
                </select>
                @error('fuel_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="car_condition">Állapot</label>
                <select id="car_condition" name="car_condition" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Új', 'Újszerű', 'Megkímélt', 'Normál', 'Használt', 'Sérült', 'Roncs'] as $cond)
                        <option value="{{ $cond }}" {{ old('car_condition') === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
                @error('car_condition') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price">Ár (Ft)</label>
                <input id="price" type="number" step="1" name="price" value="{{ old('price') }}" placeholder="pl. 1250000">
                @error('price') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mileage">Kilométer</label>
                <input id="mileage" type="number" name="mileage" value="{{ old('mileage') }}" placeholder="pl. 98000">
                @error('mileage') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="sales-form-grid" style="margin-top: 16px;">
            <div>
                <label class="checkbox-row">
                    <input type="hidden" name="documents_available" value="0">
                    <input type="checkbox" name="documents_available" value="1" {{ old('documents_available') ? 'checked' : '' }} id="docsCheck">
                    <span>Okmányok megvannak</span>
                </label>
            </div>
            <div id="docTypeWrap" style="{{ old('documents_available') ? '' : 'display:none;' }}">
                <label for="document_type">Okmány típusa</label>
                <input id="document_type" type="text" name="document_type" value="{{ old('document_type') }}" placeholder="pl. Forgalmi, Törzskönyv">
                @error('document_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="checkbox-row">
                    <input type="hidden" name="technical_inspection" value="0">
                    <input type="checkbox" name="technical_inspection" value="1" {{ old('technical_inspection') ? 'checked' : '' }}>
                    <span>Érvényes műszaki</span>
                </label>
            </div>
        </div>

        <label for="images" style="margin-top: 16px;">Képek az autóról (max 10)</label>
        <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
        @error('images') <p class="field-error">{{ $message }}</p> @enderror

        <label for="description" style="margin-top: 16px;">Leírás</label>
        <textarea id="description" name="description" rows="5" class="sale-textarea" placeholder="Részletezd az autó állapotát, extráit...">{{ old('description') }}</textarea>
        @error('description') <p class="field-error">{{ $message }}</p> @enderror

        <div class="form-actions">
            <button type="submit" class="btn sale-btn-main">Mentés</button>
            <a href="{{ route('sales.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

<script>
document.getElementById('docsCheck').addEventListener('change', function() {
    document.getElementById('docTypeWrap').style.display = this.checked ? '' : 'none';
});
</script>

@endsection
