@extends('layouts.app')

@section('content')

<section class="issues-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Üzenetek</h1>
            <p class="page-subtitle">Felhasználói üzenetek autónként csoportosítva.</p>
        </div>
    </header>

    @if($carsWithMessages->isEmpty())
        <div class="card" style="text-align:center;padding:40px;">
            <p style="opacity:.6;">Nincs üzenet.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($carsWithMessages as $car)
                <a href="{{ route('admin.messages.conversation', $car) }}" class="card issue-item-card" style="text-decoration:none;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;">
                    <div>
                        <h3 style="margin:0;display:flex;align-items:center;gap:8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2"/><path d="M9 17h6"/></svg>
                            {{ $car->make_model }}
                            <span style="opacity:.5;font-size:.85rem;font-weight:400;">— {{ $car->user?->name ?? 'Ismeretlen' }}</span>
                        </h3>
                        @if($car->last_message)
                            <p style="opacity:.6;margin:4px 0 0;font-size:.9rem;">{{ Str::limit($car->last_message->message, 80) }}</p>
                            <small style="opacity:.4;">{{ $car->last_message->created_at->diffForHumans() }}</small>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($car->unread_count > 0)
                            <span class="car-msg-badge">{{ $car->unread_count }} új</span>
                        @endif
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

@endsection
