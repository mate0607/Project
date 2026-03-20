<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autonex</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @php
        // A navbar tobb ponton hasznalja az auth adatokat, ezert egy helyen taroljuk.
        $currentUser = auth()->user();
        $isAdmin = $currentUser && $currentUser->role === 'admin';
        $isStandardUser = $currentUser && $currentUser->role === 'user';
    @endphp

    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo">Autonex</a>

        <button class="menu-toggle" type="button" aria-label="Navigáció megnyitása">☰</button>

        <div class="nav-links" id="main-nav">
            <div class="nav-main-links">
                {{-- Admin navigacio: teljes menedzsment modulok --}}
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('sales.index') }}">Eladások</a>
                    <a href="{{ route('admin.appointments.index') }}">Időpontok kezelése</a>
                    <a href="{{ route('admin.notifications.index') }}">Ügyfél értesítés</a>
                @endif

                {{-- Felhasznaloi navigacio: sajat adatokra fokuszalo menupontok --}}
                @if($isStandardUser)
                    <a href="{{ route('user.dashboard') }}">Dashboard</a>
                    <a href="{{ route('cars.index') }}">Saját autóim</a>
                    <a href="{{ route('appointments.index') }}">Időpontjaim</a>
                    <a href="{{ route('sales.index') }}">Market</a>
                @endif
            </div>

            <div class="nav-auth">
                @if($currentUser)
                    <div class="nav-profile-wrap">
                        <button type="button" class="nav-profile-icon" id="profileToggle" aria-label="Profil menü">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </button>
                        <div class="nav-profile-dropdown" id="profileDropdown">
                            <span class="nav-dd-name">{{ $currentUser->name }}</span>
                            <a href="{{ route('profile.edit') }}" class="nav-dd-item">Profil beállítások</a>
                            <form action="{{ route('logout') }}" method="POST" class="nav-dd-logout">
                                @csrf
                                <button type="submit" class="nav-dd-item nav-dd-item-logout">Kijelentkezés</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-login-btn">Bejelentkezés</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main-container">
        @yield('content')
    </main>

    @hasSection('page_footer')
        @yield('page_footer')
    @endif

    <script>
        // Mobilnezetben egyszeru menu nyitas-zaras kezeles.
        const menuToggleButton = document.querySelector('.menu-toggle');
        const mainNavigation = document.querySelector('#main-nav');

        if (menuToggleButton && mainNavigation) {
            menuToggleButton.addEventListener('click', () => {
                mainNavigation.classList.toggle('open');
            });
        }

        // Profil legordulo menu nyitas-zaras.
        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileToggle && profileDropdown) {
            profileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('nav-dd-open');
            });

            document.addEventListener('click', () => {
                profileDropdown.classList.remove('nav-dd-open');
            });
        }
    </script>
</body>
</html>