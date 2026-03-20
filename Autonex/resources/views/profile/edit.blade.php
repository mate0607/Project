@extends('layouts.app')

@push('styles')
<style>
    .profile-container { max-width: 580px; margin: 30px auto; }
    .profile-container h1 { margin: 0 0 20px; font-size: 24px; color: #e5ecff; }
    .profile-card {
        background: linear-gradient(155deg, rgba(255,255,255,0.1), rgba(255,255,255,0.03));
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.25);
    }
    .profile-form { display: grid; gap: 16px; }
    .profile-field label { display: block; color: #94a3b8; margin-bottom: 6px; font-size: 14px; }
    .profile-field input {
        width: 100%; padding: 10px 14px; border-radius: 10px;
        border: 1px solid rgba(148,163,184,0.28); background: #0f1a2e; color: #e2ecff;
        font-size: 15px; box-sizing: border-box;
    }
    .profile-field input:focus { outline: none; border-color: #4ed7f1; }
    .profile-actions { display: flex; gap: 10px; margin-top: 4px; }
    .profile-save {
        background: linear-gradient(135deg, #4ED7F1, #6FE6FC); color: #0b1220;
        border: none; padding: 10px 22px; border-radius: 10px; font-weight: 600;
        cursor: pointer; font-size: 15px; transition: 0.2s;
    }
    .profile-save:hover { filter: brightness(0.92); }
    .profile-back {
        background: rgba(148,163,184,0.12); color: #94a3b8;
        border: 1px solid rgba(148,163,184,0.2); padding: 10px 22px;
        border-radius: 10px; text-decoration: none; font-size: 15px;
    }
    .profile-back:hover { background: rgba(148,163,184,0.2); }
    .profile-success {
        background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3);
        color: #4ade80; padding: 10px 16px; border-radius: 10px; margin-bottom: 16px;
    }
    .profile-error { color: #f87171; font-size: 13px; margin-top: 4px; }
</style>
@endpush

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
