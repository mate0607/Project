@extends('layouts.app')

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
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="wl-btn wl-btn-main">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="wl-btn wl-btn-main">Bejelentkezés</a>
                    <a href="{{ route('register') }}" class="wl-btn wl-btn-soft">Regisztráció</a>
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

    <section class="wl-section reveal" style="--d: 0.05s;">
        <header class="wl-head">
            <p>DÍJAK</p>
            <h2>Elismerések</h2>
        </header>
        <div class="wl-awards-grid">
            <article class="wl-award-card reveal" style="--d: 0.06s;">
                <div class="wl-award-icon">🏆</div>
                <h3>Legjobb szerviz platform</h3>
                <p>2025 – Automotive Innovation Award</p>
            </article>
            <article class="wl-award-card reveal" style="--d: 0.1s;">
                <div class="wl-award-icon">⭐</div>
                <h3>Top felhasználói élmény</h3>
                <p>2024 – UX Excellence díj</p>
            </article>
            <article class="wl-award-card reveal" style="--d: 0.14s;">
                <div class="wl-award-icon">🥇</div>
                <h3>Leginnovatívabb megoldás</h3>
                <p>2024 – AutóTech konferencia</p>
            </article>
        </div>
    </section>

    <section class="wl-section reveal" style="--d: 0.06s;">
        <header class="wl-head">
            <p>ÁRAZÁS</p>
            <h2>Csomagjaink</h2>
        </header>
        <div class="wl-pricing-grid">
            <article class="wl-pricing-card reveal" style="--d: 0.06s;">
                <h3>Alap</h3>
                <div class="wl-pricing-price">Ingyenes</div>
                <ul>
                    <li>1 jármű kezelése</li>
                    <li>Időpont foglalás</li>
                    <li>Hiba bejelentés</li>
                </ul>
                <a href="{{ route('register') }}" class="wl-btn wl-btn-soft" style="margin-top: auto;">Regisztráció</a>
            </article>
            <article class="wl-pricing-card wl-pricing-featured reveal" style="--d: 0.1s;">
                <h3>Prémium</h3>
                <div class="wl-pricing-price">4 990 Ft<small>/hó</small></div>
                <ul>
                    <li>Korlátlan jármű</li>
                    <li>Prioritásos időpontok</li>
                    <li>Szerviz előzmények</li>
                    <li>Értesítések</li>
                </ul>
                <a href="{{ route('register') }}" class="wl-btn wl-btn-main" style="margin-top: auto;">Választom</a>
            </article>
            <article class="wl-pricing-card reveal" style="--d: 0.14s;">
                <h3>Vállalati</h3>
                <div class="wl-pricing-price">Egyedi</div>
                <ul>
                    <li>Flotta kezelés</li>
                    <li>Dedikált ügyfélszolgálat</li>
                    <li>API hozzáférés</li>
                    <li>Egyedi integrációk</li>
                </ul>
                <a href="mailto:info@autonex.hu" class="wl-btn wl-btn-soft" style="margin-top: auto;">Kapcsolat</a>
            </article>
        </div>
    </section>

    <section class="wl-section reveal" style="--d: 0.06s;">
        <header class="wl-head">
            <p>FUNKCIÓK</p>
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

    <section class="wl-section wl-cta reveal" style="--d: 0.1s;">
        <h2>Kezdd el járműveid kezelését még ma</h2>
        <p>Az Autonex segít átláthatóan kezelni autóidat és a szervizelési folyamatokat.</p>
        <div class="wl-actions wl-actions-center">
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="wl-btn wl-btn-main">Dashboard megnyitása</a>
            @else
                <a href="{{ route('register') }}" class="wl-btn wl-btn-main">Fiók létrehozása</a>
                <a href="{{ route('login') }}" class="wl-btn wl-btn-soft">Bejelentkezés</a>
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