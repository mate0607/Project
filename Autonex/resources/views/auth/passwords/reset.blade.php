@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új jelszó megadása</h1>
        <p>Állítsd be az új jelszavadat.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="anx-field">
                <label for="email">Email cím</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="password">Új jelszó</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="password-confirm">Jelszó megerősítése</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Jelszó mentése</button>
            </div>
        </form>
    </div>
</section>
@endsection
