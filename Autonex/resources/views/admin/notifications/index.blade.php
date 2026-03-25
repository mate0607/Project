@extends('layouts.app')



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
