<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autonex</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script>if(localStorage.getItem('autonex-theme')==='light')document.documentElement.classList.add('light-mode');</script>
    @stack('styles')
</head>
<body>
    <div class="pulse-left"></div>
    <div class="pulse-right"></div>
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
                            <div class="nav-dd-theme">
                                <span class="nav-dd-theme-label">Téma</span>
                                <label class="theme-switch">
                                    <input type="checkbox" id="themeToggle">
                                    <span class="theme-slider">
                                        <svg class="theme-icon theme-icon-dark" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
                                        <svg class="theme-icon theme-icon-light" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                                    </span>
                                </label>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="nav-dd-logout">
                                @csrf
                                <button type="submit" class="nav-dd-item nav-dd-item-logout">Kijelentkezés</button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Login/register buttons are on the welcome page --}}
                @endif
            </div>
        </div>
    </nav>

    <main class="main-container">
        @yield('content')
    </main>

    @hasSection('page_footer')
        @yield('page_footer')
    @endif

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="footer-logo">Autonex</span>
                <p>Modern járműkezelés és szerviz platform.</p>
            </div>
            <div class="footer-col">
                <h4>Elérhetőség</h4>
                <p>Email: info@autonex.hu</p>
                <p>Tel: +36 1 234 5678</p>
                <p>Cím: 1000 Budapest, Fő utca 1.</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} Autonex. Minden jog fenntartva.
        </div>
    </footer>

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
        }

        // Theme switch logic
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.checked = document.documentElement.classList.contains('light-mode');
            themeToggle.addEventListener('change', () => {
                document.documentElement.classList.toggle('light-mode', themeToggle.checked);
                localStorage.setItem('autonex-theme', themeToggle.checked ? 'light' : 'dark');
            });
        }
    </script>
</body>
</html>