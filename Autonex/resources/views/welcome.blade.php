@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')

<div class="welcome-long">
    <div class="ambient-glow glow-left"></div>
    <div class="ambient-glow glow-right"></div>
    <div class="ambient-grid"></div>

    <section class="wl-section wl-hero reveal" style="--d: 0.02s;">
        <div class="wl-hero-copy">
            <p class="wl-kicker">AUTONEX</p>
            <h1>Modern járműkezelés egy helyen</h1>
            <p>
                Az Autonex egy átlátható platform autók, hibák és szerviz időpontok kezelésére.
                Kövesd nyomon járműveidet, kezeld a hibákat és szervezd a szervizeléseket egy modern felületen.
            </p>
            <div class="wl-actions">
                @auth
                    <a href="{{ route('cars.index') }}" class="wl-btn wl-btn-main">Dashboard</a>
                    <a href="{{ route('cars.create') }}" class="wl-btn wl-btn-soft">Új autó hozzáadása</a>
                @else
                    <a href="{{ route('login') }}" class="wl-btn wl-btn-main">Dashboard</a>
                    <a href="{{ route('register') }}" class="wl-btn wl-btn-soft">Új autó hozzáadása</a>
                @endauth
            </div>
        </div>

        <div class="wl-preview-card">
            <div class="wl-preview-ping"></div>
            <h3>Valós idejű rendszeráttekintés</h3>
            <p>Aktív modulok csatlakoztatva</p>
            <div class="wl-preview-lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </section>

    <section class="wl-section wl-stats reveal" style="--d: 0.04s;">
        <article class="wl-stat-card reveal" style="--d: 0.06s;">
            <small>Járművek száma</small>
            <strong>{{ $carCount }}</strong>
        </article>
        <article class="wl-stat-card reveal" style="--d: 0.1s;">
            <small>Aktív hibák</small>
            <strong>12</strong>
        </article>
        <article class="wl-stat-card reveal" style="--d: 0.14s;">
            <small>Mai időpontok</small>
            <strong>8</strong>
        </article>
        <article class="wl-stat-card reveal" style="--d: 0.18s;">
            <small>Rendszer állapot</small>
            <strong>Stabil</strong>
        </article>
    </section>

    <section class="wl-section reveal" style="--d: 0.06s;">
        <header class="wl-head">
            <h2>Funkciók</h2>
        </header>
        <div class="wl-features-grid">
            <article class="wl-feature-card reveal" style="--d: 0.04s;"><h3>Autók kezelése</h3><p>Járművek nyilvántartása, adatok és szervizelőzmények kezelése.</p></article>
            <article class="wl-feature-card reveal" style="--d: 0.08s;"><h3>Hibakezelés</h3><p>Hibák rögzítése, státusz követése és javítási folyamat menedzselése.</p></article>
            <article class="wl-feature-card reveal" style="--d: 0.12s;"><h3>Időpontfoglalás</h3><p>Szerviz időpontok szervezése és kezelése.</p></article>
            <article class="wl-feature-card reveal" style="--d: 0.16s;"><h3>Modern felület</h3><p>Letisztult dashboard és gyors adatkezelés.</p></article>
            <article class="wl-feature-card reveal" style="--d: 0.2s;"><h3>Gyors rendszer</h3><p>Optimalizált működés nagyobb adatmennyiségnél is.</p></article>
            <article class="wl-feature-card reveal" style="--d: 0.24s;"><h3>Skálázható rendszer</h3><p>Később több felhasználó és modul is hozzáadható.</p></article>
        </div>
    </section>

    <section class="wl-section reveal" style="--d: 0.08s;">
        <header class="wl-head">
            <h2>Rendszer előnézet</h2>
        </header>
        <div class="wl-system-card reveal" style="--d: 0.1s;">
            <div class="wl-system-chart">
                <h4>Havi szerviz statisztika</h4>
                <div class="wl-bars">
                    <span style="--h: 32%;"></span>
                    <span style="--h: 58%;"></span>
                    <span style="--h: 44%;"></span>
                    <span style="--h: 72%;"></span>
                    <span style="--h: 62%;"></span>
                </div>
            </div>
            <div class="wl-system-feed">
                <h4>Tevékenységlista</h4>
                <ul>
                    <li>Új autó hozzáadva</li>
                    <li>Hibajegy létrehozva</li>
                    <li>Szerviz időpont rögzítve</li>
                    <li>Autó státusz frissítve</li>
                </ul>
            </div>
            <div class="wl-system-mini-stats">
                <h4>Statisztikák</h4>
                <p>Nyitott hibák: <strong>12</strong></p>
                <p>Mai időpontok: <strong>8</strong></p>
                <p>Lezárt feladatok: <strong>91%</strong></p>
            </div>
        </div>
    </section>

    <section class="wl-section reveal" style="--d: 0.1s;">
        <header class="wl-head">
            <h2>Hogyan működik</h2>
        </header>
        <div class="wl-steps">
            <article class="wl-step-card reveal" style="--d: 0.06s;">
                <span>1</span>
                <h3>Autó hozzáadása</h3>
                <p>Add hozzá járműved adatait a rendszerhez.</p>
            </article>
            <article class="wl-step-card reveal" style="--d: 0.12s;">
                <span>2</span>
                <h3>Hibák kezelése</h3>
                <p>Rögzítsd és kövesd a problémákat.</p>
            </article>
            <article class="wl-step-card reveal" style="--d: 0.18s;">
                <span>3</span>
                <h3>Időpont szervezése</h3>
                <p>Foglalj szerviz időpontot néhány kattintással.</p>
            </article>
        </div>
    </section>

    <section class="wl-section wl-cta reveal" style="--d: 0.1s;">
        <h2>Kezdd el járműveid kezelését még ma</h2>
        <p>Az Autonex segít átláthatóan kezelni autóidat és a szervizelési folyamatokat.</p>
        <div class="wl-actions wl-actions-center">
            @auth
                <a href="{{ route('cars.create') }}" class="wl-btn wl-btn-main">Fiók létrehozása</a>
                <a href="{{ route('cars.index') }}" class="wl-btn wl-btn-soft">Dashboard megnyitása</a>
            @else
                <a href="{{ route('register') }}" class="wl-btn wl-btn-main">Fiók létrehozása</a>
                <a href="{{ route('login') }}" class="wl-btn wl-btn-soft">Dashboard megnyitása</a>
            @endauth
        </div>
    </section>
</div>

<script>
    (function () {
        const elements = document.querySelectorAll('.reveal');
        const observerOptions = {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        };

        function onIntersection(entries, observer) {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        }

        const observer = new IntersectionObserver(onIntersection, observerOptions);

        elements.forEach((el) => observer.observe(el));
    })();
</script>

@endsection