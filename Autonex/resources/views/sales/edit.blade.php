@extends('layouts.app')

@section('content')

<section class="sales-form-head">
    <h1 class="page-title">Eladás szerkesztése</h1>
    <p class="page-subtitle">Frissítsd az ajánlat adatait.</p>
</section>

<div class="card sale-form-card">
    <form method="POST" action="{{ route('sales.update', $sale) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                        <option value="{{ $vt }}" {{ old('vehicle_type', $sale->vehicle_type) === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                    @endforeach
                </select>
                @error('vehicle_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="model">Modell</label>
                <input id="model" type="text" name="model" value="{{ old('model', $sale->model) }}" placeholder="pl. Toyota Corolla">
                @error('model') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="body_type">Karosszéria</label>
                <select id="body_type" name="body_type" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Sedan', 'Kombi', 'Hatchback', 'SUV', 'Kupé', 'Kabrió', 'Furgon', 'Pickup', 'Egyéb'] as $bt)
                        <option value="{{ $bt }}" {{ old('body_type', $sale->body_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
                @error('body_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="engine_cc">Köbcenti (cm³)</label>
                <input id="engine_cc" type="number" name="engine_cc" value="{{ old('engine_cc', $sale->engine_cc) }}" placeholder="pl. 1600">
                @error('engine_cc') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="fuel_type">Üzemanyag típus</label>
                <select id="fuel_type" name="fuel_type" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Benzin', 'Dízel', 'Elektromos', 'Hibrid', 'LPG', 'CNG', 'Egyéb'] as $ft)
                        <option value="{{ $ft }}" {{ old('fuel_type', $sale->fuel_type) === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                    @endforeach
                </select>
                @error('fuel_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="car_condition">Állapot</label>
                <select id="car_condition" name="car_condition" class="sale-select">
                    <option value="">Válassz...</option>
                    @foreach(['Új', 'Újszerű', 'Megkímélt', 'Normál', 'Használt', 'Sérült', 'Roncs'] as $cond)
                        <option value="{{ $cond }}" {{ old('car_condition', $sale->car_condition) === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
                @error('car_condition') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price">Ár (Ft)</label>
                <input id="price" type="number" step="1" name="price" value="{{ old('price', $sale->price) }}" placeholder="pl. 1250000">
                @error('price') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mileage">Kilométer</label>
                <input id="mileage" type="number" name="mileage" value="{{ old('mileage', $sale->mileage) }}" placeholder="pl. 98000">
                @error('mileage') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="sales-form-grid" style="margin-top: 16px;">
            <div>
                <label class="checkbox-row">
                    <input type="hidden" name="documents_available" value="0">
                    <input type="checkbox" name="documents_available" value="1" {{ old('documents_available', $sale->documents_available) ? 'checked' : '' }} id="docsCheck">
                    <span>Okmányok megvannak</span>
                </label>
            </div>
            <div id="docTypeWrap" style="{{ old('documents_available', $sale->documents_available) ? '' : 'display:none;' }}">
                <label for="document_type">Okmány típusa</label>
                <input id="document_type" type="text" name="document_type" value="{{ old('document_type', $sale->document_type) }}" placeholder="pl. Forgalmi, Törzskönyv">
                @error('document_type') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="checkbox-row">
                    <input type="hidden" name="technical_inspection" value="0">
                    <input type="checkbox" name="technical_inspection" value="1" {{ old('technical_inspection', $sale->technical_inspection) ? 'checked' : '' }}>
                    <span>Érvényes műszaki</span>
                </label>
            </div>
            <div>
                <label for="is_active">Státusz</label>
                <select id="is_active" name="is_active" class="sale-select">
                    <option value="1" {{ (string) old('is_active', (int) $sale->is_active) === '1' ? 'selected' : '' }}>Aktív</option>
                    <option value="0" {{ (string) old('is_active', (int) $sale->is_active) === '0' ? 'selected' : '' }}>Inaktív</option>
                </select>
                @error('is_active') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <label style="margin-top: 16px;">Képek az autóról</label>
        @if($sale->images->count())
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                @foreach($sale->images as $img)
                    <div style="position:relative;display:inline-block;">
                        <img src="{{ asset('storage/' . $img->path) }}" alt="Kép" style="max-height:120px;border-radius:8px;">
                        <form action="{{ route('sales.images.destroy', [$sale, $img]) }}" method="POST" style="position:absolute;top:4px;right:4px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#e74c3c;color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:14px;line-height:24px;padding:0;" title="Kép törlése">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
        <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
        @error('images') <p class="field-error">{{ $message }}</p> @enderror
        @error('images.*') <p class="field-error">{{ $message }}</p> @enderror

        <label for="description" style="margin-top: 16px;">Leírás</label>
        <textarea id="description" name="description" rows="5" class="sale-textarea" placeholder="Részletezd az autó állapotát, extráit...">{{ old('description', $sale->description) }}</textarea>
        @error('description') <p class="field-error">{{ $message }}</p> @enderror

        <div class="form-actions">
            <button type="submit" class="btn sale-btn-main">Frissítés</button>
            <a href="{{ route('sales.show', $sale) }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>
</div>

<script>
document.getElementById('docsCheck').addEventListener('change', function() {
    document.getElementById('docTypeWrap').style.display = this.checked ? '' : 'none';
});
</script>

@endsection
