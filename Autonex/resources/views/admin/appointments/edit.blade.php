@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Időpont szerkesztése (Admin)</h1>
        <p>Módosítsd az időpont és szerviz adatait.</p>
    </div>

    <div class="anx-form-card anx-form-card--lg">
        <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="{{ old('status', $appointment->status) }}">

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label>Felhasználó</label>
                    <input type="text" value="{{ $appointment->user?->name ?? '—' }}" disabled>
                </div>

                <div class="anx-field">
                    <label for="car_id">Autó</label>
                    <select id="car_id" name="car_id">
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ (string) old('car_id', $appointment->car_id) === (string) $car->id ? 'selected' : '' }}>
                                #{{ $car->id }} - {{ $car->make_model }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="date">Dátum</label>
                    <input id="date" type="date" name="date" value="{{ old('date', $appointment->date instanceof \Carbon\Carbon ? $appointment->date->format('Y-m-d') : $appointment->date) }}">
                    @error('date') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="time">Időpont</label>
                    <input id="time" type="time" name="time" value="{{ old('time', \Illuminate\Support\Carbon::parse($appointment->time)->format('H:i')) }}">
                    @error('time') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="service_stage">Szerviz állapot</label>
                    <select id="service_stage" name="service_stage">
                        <option value=""> Előjegyezve </option>
                        @foreach(['received' => 'Átvéve', 'inspected' => 'Átvizsgálva', 'in_progress' => 'Szerelés alatt', 'ready' => 'Kész, elvihető'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('service_stage', $appointment->service_stage) === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_stage') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="description">Megjegyzés</label>
                    <textarea id="description" name="description" rows="3" placeholder="Megjegyzés (opcionális)">{{ old('description', $appointment->description) }}</textarea>
                    @error('description') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="anx-divider">
            <h3 class="anx-section-title">Szerviz eredmény</h3>

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label for="total_cost">Végösszeg (Ft)</label>
                    <input id="total_cost" type="number" step="1" name="total_cost" value="{{ old('total_cost', $appointment->total_cost) }}" placeholder="Pl. 45000">
                    @error('total_cost') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="service_report">Elvégzett munkák leírása</label>
                    <textarea id="service_report" name="service_report" rows="3" placeholder="Mi történt a szerviz során?">{{ old('service_report', $appointment->service_report) }}</textarea>
                    @error('service_report') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="issues_found">Talált hibák / megjegyzések</label>
                    <textarea id="issues_found" name="issues_found" rows="3" placeholder="Nem kritikus, de figyelmet érdemlő hibák...">{{ old('issues_found', $appointment->issues_found) }}</textarea>
                    @error('issues_found') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field anx-field--full">
                    <label for="critical_warning">Kritikus figyelmeztetés</label>
                    <textarea id="critical_warning" name="critical_warning" rows="3" placeholder="Veszélyes / sürgős probléma...">{{ old('critical_warning', $appointment->critical_warning) }}</textarea>
                    @error('critical_warning') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="anx-divider">
            <h3 class="anx-section-title">Szerviz fotók feltöltése</h3>

            <div class="anx-field">
                <label>Fotók (max 10)</label>
                <div class="anx-dropzone" id="dropzone">
                    <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" id="file-input" style="display:none;">
                    <div class="anx-dropzone-prompt" id="dropzone-prompt">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p>Húzd ide a képeket vagy <span class="anx-dropzone-browse">tallózz</span></p>
                        <small>JPG, PNG, WEBP — max 5 MB / kép</small>
                    </div>
                    <div class="anx-preview-grid" id="preview-grid"></div>
                </div>
                @error('photos') <p class="field-error">{{ $message }}</p> @enderror
                @error('photos.*') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            @if($appointment->servicePhotos && $appointment->servicePhotos->count() > 0)
                <div class="anx-field">
                    <label>Feltöltött fotók</label>
                    <div class="anx-photos-grid">
                        @foreach($appointment->servicePhotos as $photo)
                            <div class="anx-photo-card">
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">
                                <span>{{ $photo->title }}</span>
                                <button type="button" class="anx-photo-delete-btn" onclick="if(confirm('Törlöd ezt a fotót?')) { document.getElementById('deletePhoto{{ $photo->id }}').submit(); }">✕</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Mentés</button>
                <a href="{{ route('admin.appointments.index') }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>

        @if($appointment->servicePhotos)
            @foreach($appointment->servicePhotos as $photo)
                <form id="deletePhoto{{ $photo->id }}" method="POST" action="{{ route('admin.service-photos.destroy', $photo) }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>
</section>

<script>
(function() {
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
        for (var i = 0; i < fileList.length; i++) {
            if (collectedFiles.files.length >= maxFiles) break;
            if (!fileList[i].type.match(/^image\/(jpeg|png|jpg|webp)$/)) continue;
            collectedFiles.items.add(fileList[i]);
            addPreview(collectedFiles.files[i], collectedFiles.files.length - 1);
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
