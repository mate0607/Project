@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/issues-board.css') }}">
@endpush

@section('content')

@php
    // A board nezethez kollekciova normalizaljuk az adatot.
    $issuesCollection = collect($issues);
    $today = now();

    // Mivel nincs kulon statusz mezo, itt idobeli heurisztikaval csoportositunk.
    $openIssues = $issuesCollection->filter(
        fn ($issue) => $issue->created_at && $issue->created_at->diffInDays($today) <= 2
    );
    $inProgressIssues = $issuesCollection->filter(
        fn ($issue) => $issue->created_at
            && $issue->created_at->diffInDays($today) > 2
            && $issue->created_at->diffInDays($today) <= 14
    );
    $resolvedIssues = $issuesCollection->filter(
        fn ($issue) => $issue->created_at && $issue->created_at->diffInDays($today) > 14
    );

    $issueColumns = [
        [
            'title' => 'Open',
            'subtitle' => 'Új hibák',
            'icon' => 'open',
            'items' => $openIssues,
            'tone' => 'open',
        ],
        [
            'title' => 'In progress',
            'subtitle' => 'Folyamatban',
            'icon' => 'progress',
            'items' => $inProgressIssues,
            'tone' => 'progress',
        ],
        [
            'title' => 'Resolved',
            'subtitle' => 'Lezárt',
            'icon' => 'resolved',
            'items' => $resolvedIssues,
            'tone' => 'resolved',
        ],
    ];
@endphp

<section class="issues-shell issues-page-enter">
    <header class="issues-topbar">
        <div>
            <h1 class="page-title">Saját hibáim</h1>
            <p class="page-subtitle">Kövesd a járműveidhez tartozó problémákat egy vizuális, gyorsan áttekinthető board nézetben.</p>
        </div>
        <a href="{{ route('issues.create') }}" class="btn issue-btn-main">
            <span class="issues-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
            </span>
            <span>Új hiba</span>
        </a>
    </header>

    <section class="issues-stats" aria-label="Hiba statisztikák">
        <article class="issues-stat-card"><p>Összes hiba</p><strong>{{ $issuesCollection->count() }}</strong></article>
        <article class="issues-stat-card"><p>Nyitott</p><strong>{{ $openIssues->count() }}</strong></article>
        <article class="issues-stat-card"><p>Folyamatban</p><strong>{{ $inProgressIssues->count() }}</strong></article>
        <article class="issues-stat-card"><p>Lezárt</p><strong>{{ $resolvedIssues->count() }}</strong></article>
    </section>

    <section class="issues-board" aria-label="Hibák csoportosítva státusz szerint">
        @foreach($issueColumns as $column)
            <article class="issues-column issues-column-{{ $column['tone'] }}">
                <header class="issues-column-head">
                    <div class="issues-column-title-wrap">
                        <span class="issues-icon" aria-hidden="true">
                            @if($column['icon'] === 'open')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v5"></path><path d="M12 16h.01"></path></svg>
                            @elseif($column['icon'] === 'progress')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12a8 8 0 1 1 8 8"></path><path d="M12 6v6l4 2"></path></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m20 6-11 11-5-5"></path></svg>
                            @endif
                        </span>
                        <div>
                            <h2>{{ $column['title'] }}</h2>
                            <p>{{ $column['subtitle'] }}</p>
                        </div>
                    </div>
                    <span class="issues-column-count">{{ $column['items']->count() }}</span>
                </header>

                <div class="issues-column-list">
                    @forelse($column['items'] as $issue)
                        <article class="issue-item-card">
                            <div class="issue-item-head">
                                <h3>{{ $issue->category }}</h3>
                                <span class="urgency urgency-{{ $issue->urgency }}">{{ strtoupper($issue->urgency) }}</span>
                            </div>

                            <div class="issue-item-meta">
                                <span>
                                    <span class="issues-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 16H9m10 0h2m-1-4 1 4-1 4h-1M3 16h2m0 0h4m-4 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2Zm14 0a2 2 0 1 1-2 2 2 2 0 0 1 2-2ZM5 16l1.3-5.1A2 2 0 0 1 8.24 9.4h6.52a2 2 0 0 1 1.94 1.5L18 16"></path></svg>
                                    </span>
                                    {{ $issue->car?->make_model ?? 'Nincs autó' }}
                                </span>
                                <span>{{ $issue->created_at?->format('Y.m.d') }}</span>
                            </div>

                            <p class="issue-item-desc">{{ \Illuminate\Support\Str::limit($issue->description, 115) }}</p>

                            <div class="issue-item-actions">
                                <a href="{{ route('issues.show', $issue) }}" class="issue-link-btn">Megnyit</a>
                                <a href="{{ route('issues.edit', $issue) }}" class="issue-link-btn">Szerkeszt</a>
                                <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="issue-link-btn issue-link-danger">Törlés</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="issues-empty-column">
                            <p>Nincs elem ebben az oszlopban.</p>
                        </div>
                    @endforelse
                </div>
            </article>
        @endforeach
    </section>
</section>

@endsection