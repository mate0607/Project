@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Üzenet küldése</h1>
        <p>{{ $sale->car?->make_model ?? 'Eladás #'.$sale->id }} &middot; {{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            <input type="hidden" name="receiver_id" value="{{ $sale->seller_id }}">

            <div class="anx-field">
                <label for="message">Üzenet</label>
                <textarea id="message" name="message" rows="5" placeholder="Írd be az üzeneted..." required maxlength="2000">{{ old('message') }}</textarea>
                @error('message') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Küldés</button>
                <a href="{{ route('sales.show', $sale) }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>
@endsection
