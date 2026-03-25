@extends('layouts.app')



@section('content')
<div class="profile-container">
    <h1>Profil beállítások</h1>

    @if(session('success'))
        <div class="profile-success">{{ session('success') }}</div>
    @endif

    <div class="profile-card">
        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="profile-field">
                <label for="name">Név</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                @error('name') <div class="profile-error">{{ $message }}</div> @enderror
            </div>

            <div class="profile-field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                @error('email') <div class="profile-error">{{ $message }}</div> @enderror
            </div>

            <div class="profile-field">
                <label for="phone">Telefonszám</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+36 30 123 4567">
                @error('phone') <div class="profile-error">{{ $message }}</div> @enderror
            </div>

            <div class="profile-actions">
                <button type="submit" class="profile-save">Mentés</button>
                <a href="{{ url()->previous() }}" class="profile-back">Vissza</a>
            </div>
        </form>
    </div>
</div>
@endsection
