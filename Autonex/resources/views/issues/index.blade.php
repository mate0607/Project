@extends('layouts.app')

@section('content')

<div class="page-head">
    <h1 class="page-title">Hibák</h1>
    <a href="{{ route('issues.create') }}" class="btn issue-btn-main">+ Új hiba</a>
</div>

<div class="card issue-card" style="margin-top: 20px;">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autó</th>
                    <th>Kategória</th>
                    <th>Sürgősség</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($issues as $issue)
                    <tr>
                        <td>{{ $issue->id }}</td>
                        <td>{{ $issue->car?->make_model ?? '—' }}</td>
                        <td>{{ $issue->category }}</td>
                        <td>
                            <span class="urgency urgency-{{ $issue->urgency }}">{{ strtoupper($issue->urgency) }}</span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('issues.show', $issue) }}" class="btn-small issue-btn">Megnyit</a>
                            <a href="{{ route('issues.edit', $issue) }}" class="btn-small issue-btn">Szerkeszt</a>

                            <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small issue-btn-delete">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Nincs még rögzített hiba.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection