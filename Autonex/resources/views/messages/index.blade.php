@extends('layouts.app')

@section('content')

<section class="issues-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Üzeneteim</h1>
            <p class="page-subtitle">Az eladásokhoz tartozó beszélgetéseid.</p>
        </div>
    </header>

    @if($messages->isEmpty())
        <div class="card" style="text-align:center;padding:40px;">
            <p style="opacity:.6;">Még nincsenek üzeneteid.</p>
            <a href="{{ route('sales.index') }}" class="btn issue-btn-main" style="margin-top:16px;">Market böngészése</a>
        </div>
    @else
        <div class="issues-board" style="display:flex;flex-direction:column;gap:12px;">
            @foreach($messages as $saleId => $thread)
                @php
                    $sale = $thread->first()->sale;
                    $lastMessage = $thread->first();
                    $unread = $thread->where('receiver_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('messages.show_conversation', $saleId) }}" class="card issue-item-card" style="text-decoration:none;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;">
                    <div>
                        <h3 style="margin:0;">{{ $sale?->car?->make_model ?? 'Eladás #'.$saleId }}</h3>
                        <p style="opacity:.6;margin:4px 0 0;font-size:.9rem;">{{ Str::limit($lastMessage->message, 80) }}</p>
                        <small style="opacity:.4;">{{ $lastMessage->created_at->diffForHumans() }}</small>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($unread > 0)
                            <span class="sale-chip sale-chip-active">{{ $unread }} új</span>
                        @endif
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

@endsection
