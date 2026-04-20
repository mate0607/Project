@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Belépés</h1>
        <p>Jelentkezz be az Autonex felületre.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="anx-field">
                <label for="email">Email cím</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="password">Jelszó</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-remember-row">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Emlékezz rám</label>
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Belépés</button>
                @if (Route::has('password.request'))
                    <a class="anx-btn-secondary" href="{{ route('password.request') }}">Elfelejtett jelszó</a>
                @endif
            </div>
        </form>

        <p class="anx-auth-link">Nincs még fiókod? <a href="{{ route('register') }}">Regisztráció</a></p>
    </div>
</section>
@endsection
