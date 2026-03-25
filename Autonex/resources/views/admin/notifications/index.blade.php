@extends('layouts.app')

@section('content')
<div class="an-container">
    <div class="an-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h1>Ügyfél értesítések</h1>
        <div style="display:flex;gap:8px;align-items:center;">
            <button type="button" id="notifSearchToggle" title="Keresés" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,0.18);border:1px solid rgba(96,165,250,0.35);cursor:pointer;transition:background 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>
            <a href="{{ route('admin.notifications.create') }}" class="ad-btn">+ Új ügyfél értesítés</a>
        </div>
    </div>

    @if(session('success'))
        <div class="an-success">{{ session('success') }}</div>
    @endif

    <div id="notifSearchPanel" style="display:none;margin-bottom:18px;">
        <div class="card app-card" style="padding:16px;">
            <form method="GET" action="{{ route('admin.notifications.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <input type="text" name="filter_name" value="{{ request('filter_name') }}" placeholder="Név" class="admin-filter-input" style="flex:1;min-width:120px;">
                <input type="text" name="filter_plate" value="{{ request('filter_plate') }}" placeholder="Rendszám" class="admin-filter-input" style="flex:1;min-width:120px;">
                <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="admin-filter-input" style="min-width:140px;">
                <button type="submit" class="btn sale-btn-main">Keresés</button>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-muted">Törlés</a>
            </form>
        </div>
    </div>

    <script>
    (function() {
        var btn = document.getElementById('notifSearchToggle');
        var panel = document.getElementById('notifSearchPanel');
        @if(request('filter_name') || request('filter_plate') || request('filter_date'))
            panel.style.display = 'block';
        @endif
        btn.addEventListener('click', function() {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });
    })();
    </script>

    <div class="notif-list" style="width:100%;">
        @forelse($notifications as $notification)
            <div class="notif-card">
                <div class="notif-card-top">
                    <div class="notif-header-left">
                        <strong>{{ $notification->title }}</strong>
                        <span class="notif-meta">
                            @if($notification->user_id)
                                <span class="an-badge-user">{{ $notification->user?->name ?? '—' }}</span>
                            @else
                                <span class="an-badge-all">Mindenki</span>
                            @endif
                            &middot; {{ $notification->created_at->format('Y.m.d H:i') }}
                        </span>
                    </div>
                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd?')" style="flex-shrink:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger-sm">Törlés</button>
                    </form>
                </div>
                <div class="notif-card-msg" data-notif-msg>
                    <p class="notif-msg-text">{{ $notification->message }}</p>
                </div>
                <button class="notif-expand-btn" data-notif-expand style="display:none;" type="button">
                    <span class="notif-chevron">&#9660;</span>
                </button>
            </div>
        @empty
            <div class="ad-card" style="padding: 24px; text-align: center; color: #94a3b8;">
                Még nincs értesítés.
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div style="margin-top: 16px;">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-notif-msg]').forEach(function(msg) {
        var text = msg.querySelector('.notif-msg-text');
        var btn = msg.parentElement.querySelector('[data-notif-expand]');
        // Check if text overflows 3 lines (~4.5em)
        if (text.scrollHeight > text.clientHeight + 1) {
            btn.style.display = 'flex';
            btn.addEventListener('click', function() {
                var card = msg.parentElement;
                card.classList.toggle('notif-expanded');
            });
        }
    });
});
</script>
@endsection
