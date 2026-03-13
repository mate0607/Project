@extends('layouts.app')

@section('content')
<div class="card form-card auth-card">
    <h1 class="page-title" style="font-size: 30px; margin-bottom: 6px;">Belépés</h1>
    <p class="page-subtitle" style="margin-bottom: 14px;">Jelentkezz be az Autonex felületre.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email cím</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password">Jelszó</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <div class="remember-row">
            <input class="checkbox-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="checkbox-label" for="remember">Emlékezz rám</label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Belépés</button>
            @if (Route::has('password.request'))
                <a class="btn btn-muted" href="{{ route('password.request') }}">Elfelejtett jelszó</a>
            @endif
        </div>
    </form>

    <div class="auth-switch">
        Nincs még fiókod?
        <a href="{{ route('register') }}">Regisztráció</a>
    </div>
</div>
@endsection
