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

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label>Felhasználó</label>
                    <input type="text" value="{{ $appointment->user?->name ?? '—' }}" disabled>
                </div>

                <div class="anx-field">
                    <label>Szerelő neve</label>
                    <input type="text" value="{{ $appointment->mechanic_name ?? '—' }}" disabled>
                    <input type="hidden" name="mechanic_name" value="{{ old('mechanic_name', $appointment->mechanic_name) }}">
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
                    <label for="status">Státusz</label>
                    <select id="status" name="status">
                        @foreach(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $appointment->status) === $status ? 'selected' : '' }}>
                                {{ strtoupper($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="anx-field">
                    <label for="service_stage">Szerviz állapot</label>
                    <select id="service_stage" name="service_stage">
                        <option value="">— Nincs beállítva —</option>
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

            <div class="anx-grid anx-grid--2">
                <div class="anx-field">
                    <label for="photo_title">Fotó címe</label>
                    <input id="photo_title" type="text" name="photo_title" placeholder="Pl. Műszaki vizsga">
                </div>

                <div class="anx-field">
                    <label for="photo">Fotó</label>
                    <input id="photo" type="file" name="photo" accept="image/*">
                    @error('photo') <p class="field-error">{{ $message }}</p> @enderror
                </div>
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
@endsection
