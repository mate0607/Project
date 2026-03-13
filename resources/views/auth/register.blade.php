@extends('layouts.app')

@section('content')
<div class="card form-card auth-card">
    <h1 class="page-title" style="font-size: 30px; margin-bottom: 6px;">Regisztráció</h1>
    <p class="page-subtitle" style="margin-bottom: 14px;">Hozd létre az Autonex fiókodat.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Név</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="email">Email cím</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password">Jelszó</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        @error('password')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password-confirm">Jelszó megerősítése</label>
        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">

        <div class="form-actions">
            <button type="submit" class="btn">Regisztráció</button>
            <a href="{{ route('login') }}" class="btn btn-muted">Már van fiókom</a>
        </div>
    </form>
</div>
@endsection
