@extends('layouts.app')

@section('content')

<section class="issues-shell msg-admin-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Üzenetek</h1>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ url()->previous() }}" class="market-action-icon" title="Vissza">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </header>

    @if($carsWithMessages->isEmpty())
        <div class="card" style="text-align:center;padding:40px;">
            <p style="opacity:.6;">Nincs üzenet.</p>
        </div>
    @else
        <div class="msg-admin-list">
            @foreach($carsWithMessages as $car)
                <a href="{{ route('admin.messages.conversation', $car) }}" class="card issue-item-card msg-admin-card">
                    <div class="msg-admin-card-body">
                        <h3 class="msg-admin-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2"/><path d="M9 17h6"/></svg>
                            {{ $car->make_model }}
                        </h3>
                        @if($car->last_message)
                            <p class="msg-admin-preview">{{ Str::limit($car->last_message->message, 80) }}</p>
                            <small class="msg-admin-time">{{ $car->last_message->created_at->diffForHumans() }}</small>
                        @endif
                    </div>
                    <div class="msg-admin-card-actions">
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
