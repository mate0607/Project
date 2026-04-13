@extends('layouts.app')

@section('content')

<section class="issues-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Üzenet szerkesztése</h1>
            <p class="page-subtitle">Módosítsd az elküldött üzeneted.</p>
        </div>
        <a href="{{ route('messages.show_conversation', $message->sale_id) }}" class="market-action-icon" title="Vissza">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </a>
    </header>

    <div class="card issue-form-card">
        <form method="POST" action="{{ route('messages.update', $message) }}">
            @csrf
            @method('PUT')

            <label for="message">Üzenet</label>
            <textarea id="message" name="message" rows="5" class="issue-textarea" placeholder="Írd be az üzeneted..." required maxlength="2000">{{ old('message', $message->message) }}</textarea>
            @error('message')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <div class="form-actions">
                <button type="submit" class="btn issue-btn-main">Mentés</button>
                <a href="{{ route('messages.show_conversation', $message->sale_id) }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</section>

@endsection
