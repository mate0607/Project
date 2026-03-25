@extends('layouts.app')


@section('content')

<div class="page-head">
    <h1 class="page-title">Időpont szerkesztése (Admin)</h1>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-muted">Vissza</a>
</div>

<div class="card app-form-card" style="margin-top: 16px;">
    <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Felhasználó</label>
        <input type="text" value="{{ $appointment->user?->name ?? '—' }}" disabled>

        <label for="car_id">Autó</label>
        <select id="car_id" name="car_id" class="app-select">
            @foreach($cars as $car)
                <option value="{{ $car->id }}" {{ (string) old('car_id', $appointment->car_id) === (string) $car->id ? 'selected' : '' }}>
                    #{{ $car->id }} - {{ $car->make_model }}
                </option>
            @endforeach
        </select>
        @error('car_id')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="date">Dátum</label>
        <input id="date" type="date" name="date" value="{{ old('date', $appointment->date) }}">
        @error('date')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="time">Időpont</label>
        <input id="time" type="time" name="time" value="{{ old('time', \Illuminate\Support\Carbon::parse($appointment->time)->format('H:i')) }}">
        @error('time')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="description">Megjegyzés</label>
        <textarea id="description" name="description" rows="4" placeholder="Megjegyzés (opcionális)">{{ old('description', $appointment->description) }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="status">Státusz</label>
        <select id="status" name="status" class="app-select">
            @foreach(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ old('status', $appointment->status) === $status ? 'selected' : '' }}>
                    {{ strtoupper($status) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="service_stage">Szerviz állapot</label>
        <select id="service_stage" name="service_stage" class="app-select">
            <option value="">— Nincs beállítva —</option>
            @foreach(['received' => 'Átvéve', 'inspected' => 'Átvizsgálva', 'in_progress' => 'Szerelés alatt', 'ready' => 'Kész, elvihető'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('service_stage', $appointment->service_stage) === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('service_stage')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <hr style="border-color: rgba(148,163,184,0.2); margin: 20px 0;">
        <h3 style="color: #f5f3ff; margin-bottom: 12px;">Szerviz eredmény</h3>

        <label for="mechanic_name">Szerelő neve</label>
        <input id="mechanic_name" type="text" name="mechanic_name" value="{{ old('mechanic_name', $appointment->mechanic_name) }}" placeholder="Pl. Kovács János">
        @error('mechanic_name')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="total_cost">Végösszeg (Ft)</label>
        <input id="total_cost" type="number" step="1" name="total_cost" value="{{ old('total_cost', $appointment->total_cost) }}" placeholder="Pl. 45000">
        @error('total_cost')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="service_report">Elvégzett munkák leírása</label>
        <textarea id="service_report" name="service_report" rows="4" placeholder="Mi történt a szerviz során?">{{ old('service_report', $appointment->service_report) }}</textarea>
        @error('service_report')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="issues_found">Talált hibák / megjegyzések</label>
        <textarea id="issues_found" name="issues_found" rows="3" placeholder="Nem kritikus, de figyelmet érdemlő hibák...">{{ old('issues_found', $appointment->issues_found) }}</textarea>
        @error('issues_found')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="critical_warning">Kritikus figyelmeztetés</label>
        <textarea id="critical_warning" name="critical_warning" rows="3" placeholder="Veszélyes / sürgős probléma...">{{ old('critical_warning', $appointment->critical_warning) }}</textarea>
        @error('critical_warning')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <hr style="border-color: rgba(148,163,184,0.2); margin: 20px 0;">
        <h3 style="color: #f5f3ff; margin-bottom: 12px;">Szerviz fotók feltöltése</h3>

        <label for="photo_title">Fotó címe</label>
        <input id="photo_title" type="text" name="photo_title" placeholder="Pl. Műszaki vizsga">

        <label for="photo">Fotó</label>
        <input id="photo" type="file" name="photo" accept="image/*">
        @error('photo')
            <p class="field-error">{{ $message }}</p>
        @enderror

        {{-- Meglévő fotók --}}
        @if($appointment->servicePhotos && $appointment->servicePhotos->count() > 0)
            <div style="margin-top: 16px;">
                <h4 style="color: #ddd6fe; margin-bottom: 10px;">Feltöltött fotók</h4>
                <div class="svc-photos-grid">
                    @foreach($appointment->servicePhotos as $photo)
                        <div class="svc-photo-card" style="position: relative;">
                            <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">
                            <span>{{ $photo->title }}</span>
                            <button type="button" class="svc-photo-delete-btn" onclick="if(confirm('Törlöd ezt a fotót?')) { document.getElementById('deletePhoto{{ $photo->id }}').submit(); }">✕</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn app-btn-main">Mentés</button>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-muted">Mégse</a>
        </div>
    </form>

    {{-- Fotó törlés formok --}}
    @if($appointment->servicePhotos)
        @foreach($appointment->servicePhotos as $photo)
            <form id="deletePhoto{{ $photo->id }}" method="POST" action="{{ route('admin.service-photos.destroy', $photo) }}" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
</div>

@endsection
