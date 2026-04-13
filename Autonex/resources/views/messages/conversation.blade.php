@extends('layouts.app')

@section('content')

<section class="issues-shell">
    <header class="issues-topbar">
        <div>
            <p class="sales-kicker">Beszélgetés</p>
            <h1 class="page-title">{{ $sale->car?->make_model ?? 'Eladás #'.$sale->id }}</h1>
            <p class="page-subtitle">{{ number_format((float) $sale->price, 0, ',', ' ') }} Ft &middot; Eladó: {{ $sale->seller?->name ?? '—' }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('sales.show', $sale) }}" class="market-action-icon" title="Hirdetés megtekintése">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            <a href="{{ route('messages.index') }}" class="market-action-icon" title="Vissza">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </header>

    <div class="card" style="padding:0;overflow:hidden;">
        <div style="max-height:500px;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px;" id="message-thread">
            @forelse($messages as $msg)
                @php $isMine = $msg->sender_id === auth()->id(); @endphp
                <div style="display:flex;flex-direction:column;align-items:{{ $isMine ? 'flex-end' : 'flex-start' }};max-width:75%;">
                    <div style="
                        background:{{ $isMine ? 'rgba(59,130,246,.15)' : 'rgba(255,255,255,.05)' }};
                        border:1px solid {{ $isMine ? 'rgba(59,130,246,.25)' : 'rgba(255,255,255,.08)' }};
                        border-radius:12px;
                        padding:10px 14px;
                        word-break:break-word;
                    ">
                        <small style="opacity:.5;font-size:.75rem;">{{ $msg->sender->name }}</small>
                        <p style="margin:4px 0 0;">{{ $msg->message }}</p>
                    </div>
                    <small style="opacity:.35;font-size:.7rem;margin-top:2px;">{{ $msg->created_at->format('Y.m.d H:i') }}</small>
                    @if($isMine)
                        <div style="display:flex;gap:8px;margin-top:2px;">
                            <form action="{{ route('messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:.7rem;opacity:.5;">Törlés</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p style="text-align:center;opacity:.5;padding:40px 0;">Még nincs üzenet ebben a beszélgetésben.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('messages.store') }}" style="border-top:1px solid rgba(255,255,255,.06);padding:16px 20px;display:flex;gap:10px;">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            <input type="hidden" name="receiver_id" value="{{ $sale->seller_id === auth()->id() ? ($messages->first()?->sender_id ?? $sale->seller_id) : $sale->seller_id }}">
            <input
                type="text"
                name="message"
                placeholder="Írd be az üzeneted..."
                required
                maxlength="2000"
                style="flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:inherit;"
            >
            <button type="submit" class="btn issue-btn-main" style="white-space:nowrap;">Küldés</button>
        </form>
    </div>
</section>

<script>
    var thread = document.getElementById('message-thread');
    if (thread) thread.scrollTop = thread.scrollHeight;
</script>

@endsection
