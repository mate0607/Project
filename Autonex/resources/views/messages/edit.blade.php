@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Üzenet szerkesztése</h1>
        <p>Módosítsd az elküldött üzeneted.</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('messages.update', $message) }}">
            @csrf
            @method('PUT')

            <div class="anx-field">
                <label for="message">Üzenet</label>
                <textarea id="message" name="message" rows="5" placeholder="Írd be az üzeneted..." required maxlength="2000">{{ old('message', $message->message) }}</textarea>
                @error('message') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Mentés</button>
                <a href="{{ route('messages.show_conversation', $message->sale_id) }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection
