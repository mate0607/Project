@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új eladás rögzítése</h1>
        <p>Töltsd ki az adatokat, és publikáld az ajánlatot.</p>
    </div>

    <div class="anx-form-card anx-form-card--lg">
        <form method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="anx-error-box">
                    <strong>Hiba:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label for="vehicle_type">Jármű típus</label>
                    <select id="vehicle_type" name="vehicle_type">
                        <option value="">Válassz...</option>
                        @foreach(['Autó', 'Motor', 'Kis teherautó', 'Mezőgazdasági gép', 'Egyéb'] as $vt)
                            <option value="{{ $vt }}" {{ old('vehicle_type') === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="model">Modell</label>
                    <input id="model" type="text" name="model" value="{{ old('model') }}" placeholder="pl. Toyota Corolla">
                    @error('model') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="body_type">Karosszéria</label>
                    <select id="body_type" name="body_type">
                        <option value="">Válassz...</option>
                        @foreach(['Sedan', 'Kombi', 'Hatchback', 'SUV', 'Kupé', 'Kabrió', 'Furgon', 'Pickup', 'Egyéb'] as $bt)
                            <option value="{{ $bt }}" {{ old('body_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                    @error('body_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="engine_cc">Köbcenti (cm³)</label>
                    <input id="engine_cc" type="number" name="engine_cc" value="{{ old('engine_cc') }}" placeholder="pl. 1600">
                    @error('engine_cc') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="fuel_type">Üzemanyag típus</label>
                    <select id="fuel_type" name="fuel_type">
                        <option value="">Válassz...</option>
                        @foreach(['Benzin', 'Dízel', 'Elektromos', 'Hibrid', 'LPG', 'CNG', 'Egyéb'] as $ft)
                            <option value="{{ $ft }}" {{ old('fuel_type') === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                        @endforeach
                    </select>
                    @error('fuel_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="car_condition">Állapot</label>
                    <select id="car_condition" name="car_condition">
                        <option value="">Válassz...</option>
                        @foreach(['Új', 'Újszerű', 'Megkímélt', 'Normál', 'Használt', 'Sérült', 'Roncs'] as $cond)
                            <option value="{{ $cond }}" {{ old('car_condition') === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                        @endforeach
                    </select>
                    @error('car_condition') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="price">Ár (Ft)</label>
                    <input id="price" type="number" step="1" name="price" value="{{ old('price') }}" placeholder="pl. 1250000">
                    @error('price') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="mileage">Kilométer</label>
                    <input id="mileage" type="number" name="mileage" value="{{ old('mileage') }}" placeholder="pl. 98000">
                    @error('mileage') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label class="anx-checkbox-row">
                        <input type="hidden" name="documents_available" value="0">
                        <input type="checkbox" name="documents_available" value="1" {{ old('documents_available') ? 'checked' : '' }} id="docsCheck">
                        <span>Okmányok megvannak</span>
                    </label>
                </div>

                <div class="anx-field" id="docTypeWrap" style="{{ old('documents_available') ? '' : 'display:none;' }}">
                    <label for="document_type">Okmány típusa</label>
                    <input id="document_type" type="text" name="document_type" value="{{ old('document_type') }}" placeholder="pl. Forgalmi, Törzskönyv">
                    @error('document_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label class="anx-checkbox-row">
                        <input type="hidden" name="technical_inspection" value="0">
                        <input type="checkbox" name="technical_inspection" value="1" {{ old('technical_inspection') ? 'checked' : '' }}>
                        <span>Érvényes műszaki</span>
                    </label>
                </div>
            </div>

            <div class="anx-field">
                <label for="images">Képek az autóról (max 10)</label>
                <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
                @error('images') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="description">Leírás</label>
                <textarea id="description" name="description" rows="5" placeholder="Részletezd az autó állapotát, extráit...">{{ old('description') }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Mentés</button>
                <a href="{{ route('sales.index') }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById('docsCheck').addEventListener('change', function() {
    document.getElementById('docTypeWrap').style.display = this.checked ? '' : 'none';
});
</script>
@endsection
