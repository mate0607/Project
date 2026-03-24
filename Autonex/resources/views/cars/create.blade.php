@extends('layouts.app')

@section('content')

<section class="car-create-wrap">
    <header class="car-create-head">
        <p class="car-create-kicker">Gépjármű hozzáadása</p>
        <h1 class="page-title car-create-title">Új autó</h1>
        <p class="car-create-subtitle">Add meg a járműved adatait. A VIN opcionális, de későbbi azonosításhoz hasznos lehet.</p>
    </header>

    <div class="card car-form-card car-create-card">
        <form method="POST" action="{{ route('cars.store') }}" class="car-create-form">
            @csrf

            <div class="car-grid">
                <div class="car-field car-field-full">
                    <label for="make_model">Típus (márka + modell)</label>
                    <input id="make_model" type="text" name="make_model" value="{{ old('make_model') }}" placeholder="pl. Toyota Corolla">
                    @error('make_model')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="car-field">
                    <label for="license_plate">Rendszám</label>
                    <input id="license_plate" type="text" name="license_plate" value="{{ old('license_plate') }}" placeholder="pl. ABC-123">
                    @error('license_plate')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="car-field">
                    <label for="year">Évjárat</label>
                    <input id="year" type="number" name="year" value="{{ old('year') }}" placeholder="pl. 2020">
                    @error('year')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="car-field car-field-full">
                    <label for="vin">VIN (opcionális)</label>
                    <input id="vin" type="text" name="vin" value="{{ old('vin') }}" placeholder="pl. 1HGCM82633A123456">
                    @error('vin')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-actions car-actions">
                <button class="btn car-btn-main" type="submit">Mentés</button>
                <a href="{{ route('cars.index') }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

    .car-create-wrap {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        gap: 20px;
        animation: carFadeUp 380ms ease-out both;
    }

    .car-create-head {
        text-align: center;
        display: grid;
        gap: 8px;
        justify-items: center;
    }

    .car-create-kicker {
        font-family: 'Manrope', sans-serif;
        letter-spacing: 0.11em;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 800;
        color: #fca5a5;
    }

    .car-create-title {
        margin-bottom: 0;
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .car-create-subtitle {
        max-width: 62ch;
        color: rgba(226, 232, 240, 0.82);
        line-height: 1.5;
    }

    .car-create-card {
        margin: 0 auto;
        width: 100%;
        max-width: 820px;
        border-radius: 20px;
        padding: clamp(18px, 2.8vw, 30px);
        background: linear-gradient(155deg, rgba(15, 23, 42, 0.9), rgba(51, 65, 85, 0.62));
        border: 1px solid rgba(148, 163, 184, 0.42);
    }

    .car-create-form {
        display: grid;
        gap: 20px;
    }

    .car-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .car-field {
        display: grid;
        gap: 6px;
    }

    .car-field-full {
        grid-column: 1 / -1;
    }

    .car-field label {
        margin-top: 0;
        margin-bottom: 0;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
    }

    .car-field .field-error {
        margin-bottom: 0;
    }

    .car-actions {
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 2px;
    }

    .car-actions .btn {
        min-width: 150px;
    }

    @media (max-width: 768px) {
        .car-create-wrap {
            gap: 16px;
        }

        .car-grid {
            grid-template-columns: 1fr;
        }

        .car-field-full {
            grid-column: auto;
        }

        .car-actions {
            width: 100%;
        }

        .car-actions .btn {
            width: 100%;
        }
    }

    @keyframes carFadeUp {
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

@endsection