@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Eladás szerkesztése</h1>
        <p>Frissítsd az ajánlat adatait.</p>
    </div>

    <div class="anx-form-card anx-form-card--lg">
        <form method="POST" action="{{ route('sales.update', $sale) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                {{-- Vehicle Type --}}
                <div class="anx-field">
                    <label for="vehicle_type">Jármű típus</label>
                    <select id="vehicle_type" name="vehicle_type">
                        <option value="">Válassz...</option>
                        @foreach(['Autó', 'Motor', 'Kis teherautó', 'Mezőgazdasági gép', 'Egyéb'] as $vt)
                            <option value="{{ $vt }}" {{ old('vehicle_type', $sale->vehicle_type) === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Brand (autocomplete) --}}
                <div class="anx-field anx-autocomplete-wrap">
                    <label for="brand">Márka</label>
                    <input id="brand" type="text" name="brand" value="{{ old('brand', $sale->brand) }}" placeholder="Kezdj el gépelni..." autocomplete="off">
                    <div id="brand-dropdown" class="anx-autocomplete-dropdown"></div>
                    @error('brand') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Model (autocomplete) --}}
                <div class="anx-field anx-autocomplete-wrap">
                    <label for="model">Modell</label>
                    <input id="model" type="text" name="model" value="{{ old('model', $sale->model) }}" placeholder="Előbb válassz márkát..." autocomplete="off">
                    <div id="model-dropdown" class="anx-autocomplete-dropdown"></div>
                    @error('model') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Body Type (dynamic) --}}
                <div class="anx-field">
                    <label for="body_type">Karosszéria</label>
                    <select id="body_type" name="body_type">
                        <option value="">Válassz...</option>
                    </select>
                    @error('body_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="engine_cc">Köbcenti (cm³)</label>
                    <input id="engine_cc" type="number" name="engine_cc" value="{{ old('engine_cc', $sale->engine_cc) }}" placeholder="pl. 1600">
                    @error('engine_cc') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="fuel_type">Üzemanyag típus</label>
                    <select id="fuel_type" name="fuel_type">
                        <option value="">Válassz...</option>
                        @foreach(['Benzin', 'Dízel', 'Elektromos', 'Hibrid', 'LPG', 'CNG', 'Egyéb'] as $ft)
                            <option value="{{ $ft }}" {{ old('fuel_type', $sale->fuel_type) === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                        @endforeach
                    </select>
                    @error('fuel_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="car_condition">Állapot</label>
                    <select id="car_condition" name="car_condition">
                        <option value="">Válassz...</option>
                        @foreach(['Új', 'Újszerű', 'Megkímélt', 'Normál', 'Használt', 'Sérült', 'Roncs'] as $cond)
                            <option value="{{ $cond }}" {{ old('car_condition', $sale->car_condition) === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                        @endforeach
                    </select>
                    @error('car_condition') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="price">Ár (Ft)</label>
                    <input id="price" type="number" step="1" name="price" value="{{ old('price', $sale->price) }}" placeholder="pl. 1250000">
                    @error('price') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="mileage">Kilométer</label>
                    <input id="mileage" type="number" name="mileage" value="{{ old('mileage', $sale->mileage) }}" placeholder="pl. 98000">
                    @error('mileage') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label class="anx-checkbox-row">
                        <input type="hidden" name="documents_available" value="0">
                        <input type="checkbox" name="documents_available" value="1" {{ old('documents_available', $sale->documents_available) ? 'checked' : '' }} id="docsCheck">
                        <span>Okmányok megvannak</span>
                    </label>
                </div>

                <div class="anx-field" id="docTypeWrap" style="{{ old('documents_available', $sale->documents_available) ? '' : 'display:none;' }}">
                    <label for="document_type">Okmány típusa</label>
                    <input id="document_type" type="text" name="document_type" value="{{ old('document_type', $sale->document_type) }}" placeholder="pl. Forgalmi, Törzskönyv">
                    @error('document_type') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label class="anx-checkbox-row">
                        <input type="hidden" name="technical_inspection" value="0">
                        <input type="checkbox" name="technical_inspection" value="1" {{ old('technical_inspection', $sale->technical_inspection) ? 'checked' : '' }}>
                        <span>Érvényes műszaki</span>
                    </label>
                </div>

                <div class="anx-field">
                    <label for="is_active">Státusz</label>
                    <select id="is_active" name="is_active">
                        <option value="1" {{ (string) old('is_active', (int) $sale->is_active) === '1' ? 'selected' : '' }}>Aktív</option>
                        <option value="0" {{ (string) old('is_active', (int) $sale->is_active) === '0' ? 'selected' : '' }}>Inaktív</option>
                    </select>
                    @error('is_active') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="anx-field">
                <label>Képek</label>
                @if($sale->images->count())
                    <div class="anx-image-grid" id="existing-images">
                        @foreach($sale->images as $img)
                            <div class="anx-image-thumb">
                                <img src="{{ asset('storage/' . $img->path) }}" alt="Kép">
                                <button type="button" class="anx-img-delete" title="Kép törlése"
                                    data-delete-url="{{ route('sales.images.destroy', [$sale, $img]) }}"
                                    data-token="{{ csrf_token() }}">&times;</button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="anx-dropzone" id="dropzone" style="margin-top:8px;">
                    <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" id="file-input" style="display:none;">
                    <div class="anx-dropzone-prompt" id="dropzone-prompt">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p>Húzd ide az új képeket vagy <span class="anx-dropzone-browse">tallózz</span></p>
                        <small>JPG, PNG, WEBP — max 5 MB / kép</small>
                    </div>
                    <div class="anx-preview-grid" id="preview-grid"></div>
                </div>
                @error('images') <p class="field-error">{{ $message }}</p> @enderror
                @error('images.*') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="description">Leírás</label>
                <textarea id="description" name="description" rows="5" placeholder="Részletezd a jármű állapotát, extráit...">{{ old('description', $sale->description) }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Frissítés</button>
                <a href="{{ route('sales.show', $sale) }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>

<script>
(function() {
    const API = '/api/vehicles';
    const vehicleType = document.getElementById('vehicle_type');
    const brandInput  = document.getElementById('brand');
    const modelInput  = document.getElementById('model');
    const bodyType    = document.getElementById('body_type');
    const brandDrop   = document.getElementById('brand-dropdown');
    const modelDrop   = document.getElementById('model-dropdown');

    let debounceTimer = null;

    function debounce(fn, ms) {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fn(...args), ms);
        };
    }

    function hideAllDropdowns() {
        brandDrop.innerHTML = '';
        brandDrop.classList.remove('open');
        modelDrop.innerHTML = '';
        modelDrop.classList.remove('open');
    }

    function showDropdown(dropdown, items, input, onSelect) {
        dropdown.innerHTML = '';
        if (!items.length) {
            dropdown.classList.remove('open');
            return;
        }
        items.forEach(function(item) {
            var div = document.createElement('div');
            div.className = 'anx-autocomplete-item';
            div.textContent = item;
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                input.value = item;
                dropdown.innerHTML = '';
                dropdown.classList.remove('open');
                if (onSelect) onSelect(item);
            });
            dropdown.appendChild(div);
        });
        dropdown.classList.add('open');
    }

    async function fetchJson(url) {
        var res = await fetch(url);
        return res.json();
    }

    vehicleType.addEventListener('change', function() {
        brandInput.value = '';
        modelInput.value = '';
        modelInput.placeholder = 'Előbb válassz márkát...';
        hideAllDropdowns();
        loadBodyTypes(this.value);

        if (this.value) {
            brandInput.placeholder = 'Kezdj el gépelni...';
        } else {
            brandInput.placeholder = 'Előbb válassz járműtípust...';
        }
    });

    brandInput.addEventListener('input', debounce(function() {
        var type = vehicleType.value;
        if (!type) { brandInput.placeholder = 'Előbb válassz járműtípust...'; return; }
        var q = brandInput.value.trim();
        fetchJson(API + '/brands?type=' + encodeURIComponent(type) + '&q=' + encodeURIComponent(q))
            .then(function(brands) {
                showDropdown(brandDrop, brands, brandInput, function() {
                    modelInput.value = '';
                    modelInput.placeholder = 'Kezdj el gépelni...';
                    modelDrop.innerHTML = '';
                    modelDrop.classList.remove('open');
                });
            });
    }, 200));

    brandInput.addEventListener('focus', function() {
        var type = vehicleType.value;
        if (!type) return;
        fetchJson(API + '/brands?type=' + encodeURIComponent(type) + '&q=' + encodeURIComponent(brandInput.value.trim()))
            .then(function(brands) {
                showDropdown(brandDrop, brands, brandInput, function() {
                    modelInput.value = '';
                    modelInput.placeholder = 'Kezdj el gépelni...';
                });
            });
    });

    brandInput.addEventListener('blur', function() {
        setTimeout(function() { brandDrop.innerHTML = ''; brandDrop.classList.remove('open'); }, 150);
    });

    modelInput.addEventListener('input', debounce(function() {
        var type = vehicleType.value;
        var brand = brandInput.value.trim();
        if (!type || !brand) { modelInput.placeholder = 'Előbb válassz márkát...'; return; }
        var q = modelInput.value.trim();
        fetchJson(API + '/models?type=' + encodeURIComponent(type) + '&brand=' + encodeURIComponent(brand) + '&q=' + encodeURIComponent(q))
            .then(function(models) {
                showDropdown(modelDrop, models, modelInput, null);
            });
    }, 200));

    modelInput.addEventListener('focus', function() {
        var type = vehicleType.value;
        var brand = brandInput.value.trim();
        if (!type || !brand) return;
        fetchJson(API + '/models?type=' + encodeURIComponent(type) + '&brand=' + encodeURIComponent(brand) + '&q=' + encodeURIComponent(modelInput.value.trim()))
            .then(function(models) {
                showDropdown(modelDrop, models, modelInput, null);
            });
    });

    modelInput.addEventListener('blur', function() {
        setTimeout(function() { modelDrop.innerHTML = ''; modelDrop.classList.remove('open'); }, 150);
    });

    function loadBodyTypes(type) {
        var oldVal = '{{ old('body_type', $sale->body_type) }}';
        bodyType.innerHTML = '<option value="">Válassz...</option>';
        if (!type) return;
        fetchJson(API + '/body-types?type=' + encodeURIComponent(type))
            .then(function(types) {
                types.forEach(function(bt) {
                    var opt = document.createElement('option');
                    opt.value = bt;
                    opt.textContent = bt;
                    if (bt === oldVal) opt.selected = true;
                    bodyType.appendChild(opt);
                });
            });
    }

    document.getElementById('docsCheck').addEventListener('change', function() {
        document.getElementById('docTypeWrap').style.display = this.checked ? '' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.anx-autocomplete-wrap')) {
            hideAllDropdowns();
        }
    });

    // Init: load body types for existing vehicle type
    if (vehicleType.value) {
        loadBodyTypes(vehicleType.value);
        if (brandInput.value) modelInput.placeholder = 'Kezdj el gépelni...';
    }

    // ── Delete existing images via fetch (avoids nested forms) ──
    document.querySelectorAll('.anx-img-delete[data-delete-url]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('Biztosan törlöd ezt a képet?')) return;
            var url = this.dataset.deleteUrl;
            var token = this.dataset.token;
            var thumb = this.closest('.anx-image-thumb');
            fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            }).then(function(resp) {
                if (resp.ok) thumb.remove();
            });
        });
    });

    // ── Multi-file uploader with drag & drop ──
    var fileInput = document.getElementById('file-input');
    var dropzone = document.getElementById('dropzone');
    var previewGrid = document.getElementById('preview-grid');
    var prompt = document.getElementById('dropzone-prompt');
    var collectedFiles = new DataTransfer();

    function updateFileInput() {
        fileInput.files = collectedFiles.files;
        prompt.style.display = collectedFiles.files.length ? 'none' : '';
    }

    function addFiles(fileList) {
        var maxFiles = 10;
        var existingCount = document.querySelectorAll('#existing-images .anx-image-thumb').length;
        for (var i = 0; i < fileList.length; i++) {
            if (collectedFiles.files.length + existingCount >= maxFiles) break;
            if (!fileList[i].type.match(/^image\/(jpeg|png|jpg|webp)$/)) continue;
            collectedFiles.items.add(fileList[i]);
            addPreview(fileList[i], collectedFiles.files.length - 1);
        }
        updateFileInput();
    }

    function addPreview(file, idx) {
        var wrap = document.createElement('div');
        wrap.className = 'anx-preview-item';
        wrap.dataset.idx = idx;

        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.onload = function() { URL.revokeObjectURL(this.src); };

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'anx-preview-remove';
        btn.innerHTML = '&times;';
        btn.addEventListener('click', function() {
            removeFile(parseInt(wrap.dataset.idx));
        });

        wrap.appendChild(img);
        wrap.appendChild(btn);
        previewGrid.appendChild(wrap);
    }

    function removeFile(idx) {
        var newDT = new DataTransfer();
        for (var i = 0; i < collectedFiles.files.length; i++) {
            if (i !== idx) newDT.items.add(collectedFiles.files[i]);
        }
        collectedFiles = newDT;
        rebuildPreviews();
        updateFileInput();
    }

    function rebuildPreviews() {
        previewGrid.innerHTML = '';
        for (var i = 0; i < collectedFiles.files.length; i++) {
            addPreview(collectedFiles.files[i], i);
        }
    }

    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('.anx-preview-remove')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) addFiles(this.files);
    });

    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('anx-dropzone-active');
    });

    dropzone.addEventListener('dragleave', function() {
        this.classList.remove('anx-dropzone-active');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('anx-dropzone-active');
        if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
    });
})();
</script>
@endsection
