@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <style>
        .an-container { max-width: 1100px; margin: 20px auto; }
        .an-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .an-header h1 { margin: 0; font-size: 24px; }
        .an-table { width: 100%; border-collapse: collapse; }
        .an-table th, .an-table td { padding: 12px 14px; text-align: left; border-bottom: 1px solid rgba(148,163,184,0.18); }
        .an-table th { color: #94a3b8; font-size: 13px; text-transform: uppercase; }
        .an-table td { color: #e2ecff; }
        .an-badge-all { background: #1e3a5f; color: #60a5fa; padding: 3px 10px; border-radius: 8px; font-size: 12px; }
        .an-badge-user { background: #1a3340; color: #4ed7f1; padding: 3px 10px; border-radius: 8px; font-size: 12px; }
        .an-empty { text-align: center; color: #64748b; padding: 32px; }
        .an-delete-form { display: inline; }
        .an-delete-btn { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); padding: 5px 12px; border-radius: 8px; cursor: pointer; font-size: 13px; }
        .an-delete-btn:hover { background: rgba(239,68,68,0.3); }
        .an-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 10px 16px; border-radius: 10px; margin-bottom: 16px; }
    </style>
@endpush

@section('content')
<div class="an-container">
    <div class="an-header">
        <h1>Ügyfél értesítés</h1>
        <a href="{{ route('admin.notifications.create') }}" class="ad-btn">+ Új ügyfél értesítés</a>
    </div>

    @if(session('success'))
        <div class="an-success">{{ session('success') }}</div>
    @endif

    <div class="ad-card" style="padding: 0; overflow: hidden;">
        <table class="an-table">
            <thead>
                <tr>
                    <th>Cím</th>
                    <th>Üzenet</th>
                    <th>Címzett</th>
                    <th>Dátum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td><strong>{{ $notification->title }}</strong></td>
                        <td>{{ Str::limit($notification->message, 60) }}</td>
                        <td>
                            @if($notification->user_id)
                                <span class="an-badge-user">{{ $notification->user?->name ?? '—' }}</span>
                            @else
                                <span class="an-badge-all">Mindenki</span>
                            @endif
                        </td>
                        <td>{{ $notification->created_at->format('Y.m.d H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="an-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="an-delete-btn">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="an-empty">Még nincs értesítés.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notifications->hasPages())
        <div style="margin-top: 16px;">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
