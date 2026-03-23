@extends('layouts.app')

@section('content')

<section class="appointment-create-wrap">
    <header class="appointment-create-head">
        <p class="appointment-create-kicker">Szervizfoglalás</p>
        <h1 class="page-title appointment-create-title">Új időpont</h1>
        <p class="appointment-create-subtitle">Add meg az autót és az időpont részleteit. Minden mező áttekinthetően középre rendezve jelenik meg, mobilon is kényelmes kitöltéssel.</p>
    </header>

    <div class="card app-form-card appointment-create-card">
        <form method="POST" action="{{ route('appointments.store') }}" class="appointment-create-form">
            @csrf

            <div class="appointment-grid">
                <div class="appointment-field appointment-field-full">
                    <label for="car_id">Autó</label>
                    <select id="car_id" name="car_id" class="app-select">
                        <option value="">Válassz autót...</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ (string) old('car_id') === (string) $car->id ? 'selected' : '' }}>
                                #{{ $car->id }} - {{ $car->make_model }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="appointment-field">
                    <label for="date">Dátum</label>
                    <input id="date" type="date" name="date" value="{{ old('date') }}">
                    @error('date')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="appointment-field">
                    <label for="time">Időpont</label>
                    <input id="time" type="time" name="time" value="{{ old('time') }}">
                    @error('time')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="appointment-field appointment-field-full">
                    <label for="service">Szerviz típusa</label>
                    <input id="service" type="text" name="service" value="{{ old('service') }}" placeholder="Pl.: olajcsere, fékellenőrzés (opcionális)">
                    @error('service')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="appointment-field appointment-field-full">
                    <label for="description">Megjegyzés</label>
                    <textarea id="description" name="description" rows="4" class="app-textarea" placeholder="Írj megjegyzést az időponthoz (opcionális)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-actions appointment-actions">
                <button type="submit" class="btn app-btn-main">Mentés</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

    .appointment-create-wrap {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        gap: 20px;
        animation: appointmentFadeUp 380ms ease-out both;
    }

    .appointment-create-head {
        text-align: center;
        display: grid;
        gap: 8px;
        justify-items: center;
    }

    .appointment-create-kicker {
        font-family: 'Manrope', sans-serif;
        letter-spacing: 0.11em;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 800;
        color: #fda4af;
    }

    .appointment-create-title {
        margin-bottom: 0;
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .appointment-create-subtitle {
        max-width: 62ch;
        color: rgba(226, 232, 240, 0.82);
        line-height: 1.5;
    }

    .appointment-create-card {
        margin: 0 auto;
        width: 100%;
        max-width: 820px;
        border-radius: 20px;
        padding: clamp(18px, 2.8vw, 30px);
        background: linear-gradient(155deg, rgba(15, 23, 42, 0.9), rgba(51, 65, 85, 0.62));
        border: 1px solid rgba(148, 163, 184, 0.42);
    }

    .appointment-create-form {
        display: grid;
        gap: 20px;
    }

    .appointment-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .appointment-field {
        display: grid;
        gap: 6px;
    }

    .appointment-field-full {
        grid-column: 1 / -1;
    }

    .appointment-field label {
        margin-top: 0;
        margin-bottom: 0;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
    }

    .appointment-field .field-error {
        margin-bottom: 0;
    }

    .appointment-actions {
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 2px;
    }

    .appointment-actions .btn {
        min-width: 150px;
    }

    @media (max-width: 768px) {
        .appointment-create-wrap {
            gap: 16px;
        }

        .appointment-grid {
            grid-template-columns: 1fr;
        }

        .appointment-field-full {
            grid-column: auto;
        }

        .appointment-actions {
            width: 100%;
        }

        .appointment-actions .btn {
            width: 100%;
        }
    }

    @keyframes appointmentFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
</div>

@endsection