@extends('layouts.app')

@push('styles')
<style>
    .admin-user-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 108px;
        padding: 7px 12px;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.22);
        background: rgba(15, 23, 42, 0.55);
        color: #dbe4ff;
        font-size: 0.84rem;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease, transform .18s ease;
    }

    .admin-user-action:hover {
        background: rgba(30, 41, 59, 0.88);
        border-color: rgba(96, 165, 250, 0.38);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .admin-user-action-danger {
        border-color: rgba(239, 68, 68, 0.28);
        background: rgba(127, 29, 29, 0.18);
        color: #fca5a5;
    }

    .admin-user-action-danger:hover {
        background: rgba(127, 29, 29, 0.32);
        border-color: rgba(248, 113, 113, 0.5);
        color: #fecaca;
    }

    .admin-user-action-restore {
        border-color: rgba(34, 197, 94, 0.26);
        background: rgba(20, 83, 45, 0.18);
        color: #86efac;
    }

    .admin-user-action-restore:hover {
        background: rgba(20, 83, 45, 0.34);
        border-color: rgba(74, 222, 128, 0.45);
        color: #dcfce7;
    }
</style>
@endpush

@section('content')

<section class="issues-shell">
    <header class="issues-topbar" style="margin-bottom:14px;">
        <div>
            <h1 class="page-title">Felhasználók</h1>
            @if($users->count() > 0)
                <p style="margin:6px 0 0;opacity:.7;font-size:.92rem;">
                    Megjelenítve {{ $users->firstItem() }}-{{ $users->lastItem() }} / {{ $users->total() }} felhasználó.
                </p>
            @endif
        </div>
    </header>

    <div class="card" style="padding:14px;margin-bottom:12px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Keresés név, email, telefon alapján..."
                style="flex:1;min-width:220px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:10px 12px;color:inherit;"
            >
            <button class="btn issue-btn-main" type="submit">Keresés</button>
        </form>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);text-align:left;">
                        <th style="padding:12px;">Név</th>
                        <th style="padding:12px;">Email</th>
                        <th style="padding:12px;">Szerepkör</th>
                        <th style="padding:12px;">Státusz</th>
                        <th style="padding:12px;">Létrehozva</th>
                        <th style="padding:12px;text-align:right;">Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-top:1px solid rgba(255,255,255,.08);">
                            <td style="padding:12px;">{{ $user->name }}</td>
                            <td style="padding:12px;">{{ $user->email }}</td>
                            <td style="padding:12px;">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</td>
                            <td style="padding:12px;">
                                @if($user->trashed())
                                    <span style="display:inline-block;background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.4);color:#fca5a5;padding:2px 8px;border-radius:999px;font-size:.78rem;">Törölt</span>
                                @else
                                    <span style="display:inline-block;background:rgba(34,197,94,.14);border:1px solid rgba(34,197,94,.35);color:#86efac;padding:2px 8px;border-radius:999px;font-size:.78rem;">Aktív</span>
                                @endif
                            </td>
                            <td style="padding:12px;">{{ $user->created_at?->format('Y.m.d H:i') }}</td>
                            <td style="padding:12px;text-align:right;white-space:nowrap;">
                                @if($user->trashed())
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="admin-user-action admin-user-action-restore">Visszaállítás</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Biztosan soft delete-elni szeretnéd?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-user-action admin-user-action-danger">Soft delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:20px;text-align:center;opacity:.65;">Nincs találat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div class="market-pagination" style="margin-top:14px;justify-content:center;">
            @if($users->onFirstPage())
                <span class="market-page-arrow disabled">&laquo; Előző</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="market-page-arrow">&laquo; Előző</a>
            @endif

            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if($page === $users->currentPage())
                    <span class="market-page-num active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="market-page-num">{{ $page }}</a>
                @endif
            @endforeach

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="market-page-arrow">Következő &raquo;</a>
            @else
                <span class="market-page-arrow disabled">Következő &raquo;</span>
            @endif
        </div>
    @endif
</section>

@endsection
