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
                    <a href="{{ route('cars.index') }}">Autók kezelése</a>
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
                    <span class="nav-user">{{ $currentUser->name }}</span>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="nav-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
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
    </script>
</body>
</html>