@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Jelszó megerősítése</h1>
        <p>A folytatáshoz kérjük, erősítsd meg a jelszavadat.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="anx-field">
                <label for="password">Jelszó</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Megerősítés</button>
                @if (Route::has('password.request'))
                    <a class="anx-btn-secondary" href="{{ route('password.request') }}">Elfelejtett jelszó</a>
                @endif
            </div>
        </form>
    </div>
</section>
@endsection
