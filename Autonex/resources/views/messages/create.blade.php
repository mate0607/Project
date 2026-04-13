@extends('layouts.app')

@section('content')

<section class="issues-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Üzenet küldése</h1>
            <p class="page-subtitle">{{ $sale->car?->make_model ?? 'Eladás #'.$sale->id }} &middot; {{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</p>
        </div>
        <a href="{{ route('sales.show', $sale) }}" class="market-action-icon" title="Vissza">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </a>
    </header>

    <div class="card issue-form-card">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            <input type="hidden" name="receiver_id" value="{{ $sale->seller_id }}">

            <label for="message">Üzenet</label>
            <textarea id="message" name="message" rows="5" class="issue-textarea" placeholder="Írd be az üzeneted..." required maxlength="2000">{{ old('message') }}</textarea>
            @error('message')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <div class="form-actions">
                <button type="submit" class="btn issue-btn-main">Küldés</button>
                <a href="{{ route('sales.show', $sale) }}" class="btn btn-muted">Mégse</a>
            </div>
        </form>
    </div>
</section>

@endsection
