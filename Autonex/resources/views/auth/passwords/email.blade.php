@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Jelszó visszaállítás</h1>
        <p>Add meg az email címedet, és küldünk egy visszaállító linket.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        @if (session('status'))
            <div class="anx-success-box">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="anx-field">
                <label for="email">Email cím</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Link küldése</button>
                <a href="{{ route('login') }}" class="anx-btn-secondary">Vissza</a>
            </div>
        </form>
    </div>
</section>
@endsection
