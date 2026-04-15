@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Regisztráció</h1>
        <p>Hozd létre az Autonex fiókodat.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="anx-field">
                <label for="name">Név</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="email">Email cím</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="password">Jelszó</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="password-confirm">Jelszó megerősítése</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Regisztráció</button>
                <a href="{{ route('login') }}" class="anx-btn-secondary">Már van fiókom</a>
            </div>
        </form>
    </div>
</section>
@endsection
