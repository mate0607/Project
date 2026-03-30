@extends('layouts.app')

@push('styles')
<style>
    .site-footer { display: none; }
    .main-container { padding: 0; max-width: none; }
</style>
@endpush

@section('content')

<div class="landing-v2">

    {{-- ===== HERO ===== --}}
    <section class="lv2-hero">
        <div class="lv2-hero-overlay"></div>

        <div class="lv2-hero-inner">
            {{-- Left: headline --}}
            <div class="lv2-hero-text">
                <h1>Professzionális<br>járműkezelés<br>és szerviz</h1>
                <p>Az Autonex egy átlátható platform autók, hibák és szerviz időpontok kezelésére. Kövesd nyomon járműveidet egyszerűen.</p>
            </div>

            {{-- Right: auth card --}}
            <div class="lv2-hero-card">
                @auth
                    <h2>Üdvözöljük!</h2>
                    <p class="lv2-card-sub">Lépjen be a kezelőfelületre</p>
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="lv2-btn lv2-btn-red">Dashboard megnyitása</a>
                @else
                    <h2>Lépjen be</h2>
                    <p class="lv2-card-sub">Jelentkezzen be vagy hozzon létre fiókot</p>
                    <div class="lv2-card-actions">
                        <a href="{{ route('login') }}" class="lv2-btn lv2-btn-red">Bejelentkezés</a>
                        <a href="{{ route('register') }}" class="lv2-btn lv2-btn-outline">Regisztráció</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    {{-- ===== RED STRIP ===== --}}
    <div class="lv2-red-strip"></div>

    {{-- ===== FEATURES GRID ===== --}}
    <section class="lv2-features">
        <div class="lv2-features-grid">
            <div class="lv2-feat-card">
                <div class="lv2-feat-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2M5 17l-1 4h2l1-4m10 0l1 4h2l-1-4"/><circle cx="7.5" cy="11.5" r="1.5"/><circle cx="16.5" cy="11.5" r="1.5"/><path d="M5 7l2-3h10l2 3"/></svg>
                </div>
                <h3>Járműkezelés</h3>
                <p>Autók nyilvántartása, adatok és szervizelőzmények egy helyen.</p>
            </div>

            <div class="lv2-feat-card lv2-feat-highlight">
                <div class="lv2-feat-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <h3>Szerviz & Diagnosztika</h3>
                <p>Hibák rögzítése, javítási folyamatok és szerviz időpontok kezelése.</p>
            </div>

            <div class="lv2-feat-card">
                <div class="lv2-feat-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3>Időpontfoglalás</h3>
                <p>Szerviz időpontok szervezése és áttekintése pár kattintással.</p>
            </div>
        </div>
    </section>

</div>

@endsection