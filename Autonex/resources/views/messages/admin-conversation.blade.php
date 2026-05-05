@extends('layouts.app')

@section('content')

<section class="issues-shell msg-conv-shell">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">
                @if($car->sales->first())
                    <a href="{{ route('sales.show', $car->sales->first()) }}" class="msg-conv-car-link">{{ $car->make_model }}</a>
                @else
                    {{ $car->make_model }}
                @endif
            </h1>
        </div>
        <div class="msg-conv-header-actions">
            <a href="{{ route('admin.messages.index') }}" class="market-action-icon" title="Vissza">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </header>

    <div class="card msg-conv-card">
        <div class="msg-conv-thread" id="message-thread">
            @forelse($messages as $msg)
                @php $isMine = $msg->sender_id === auth()->id(); @endphp
                <div class="msg-conv-row {{ $isMine ? 'msg-conv-row-mine' : 'msg-conv-row-theirs' }}">
                    <div class="msg-conv-bubble {{ $isMine ? 'msg-conv-bubble-mine' : 'msg-conv-bubble-theirs' }}">
                        <small class="msg-conv-sender">{{ $msg->sender?->name ?? 'Törölt felhasználó' }}</small>
                        <p class="msg-conv-text">{{ $msg->message }}</p>
                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" style="margin-top:6px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Biztosan törölni szeretnéd ezt az üzenetet?');" style="background:transparent;border:none;color:#fca5a5;padding:0;font-size:.78rem;cursor:pointer;">
                                Moderálás (törlés)
                            </button>
                        </form>
                    </div>
                    <small class="msg-conv-time">{{ $msg->created_at->format('Y.m.d H:i') }}</small>
                </div>
            @empty
                <p class="msg-conv-empty">Még nincs üzenet.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('cars.messages.store', $car) }}" class="msg-conv-form">
            @csrf
            <input type="text" name="message" placeholder="Válasz írása..." required maxlength="2000" class="msg-conv-input">
            <button type="submit" class="btn issue-btn-main msg-conv-send">Küldés</button>
        </form>
    </div>
</section>

<script>
    var thread = document.getElementById('message-thread');
    if (thread) thread.scrollTop = thread.scrollHeight;
</script>

@endsection
