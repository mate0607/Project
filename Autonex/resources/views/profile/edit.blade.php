@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Profil beállítások</h1>
        <p>Frissítsd a személyes adataidat.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        @if(session('success'))
            <div class="anx-success-box">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="anx-field">
                <label for="name">Név</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="phone">Telefonszám</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+36 30 123 4567">
                @error('phone') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Mentés</button>
                <a href="{{ url()->previous() }}" class="anx-btn-secondary">Vissza</a>
            </div>
        </form>
    </div>
</section>
@endsection
