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
    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo">Autonex</a>

        <button class="menu-toggle" type="button" aria-label="Navigáció megnyitása">☰</button>

        <div class="nav-links" id="main-nav">
            <div class="nav-main-links">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('cars.index') }}">Autók kezelése</a>
                    <a href="{{ route('issues.index') }}">Hibák</a>
                    <a href="{{ route('sales.index') }}">Eladások</a>
                    <a href="{{ route('admin.appointments.index') }}">Időpontok kezelése</a>
                @endif

                @if(auth()->check() && auth()->user()->role === 'user')
                    <a href="{{ route('user.dashboard') }}">Dashboard</a>
                    <a href="{{ route('cars.index') }}">Saját autóim</a>
                    <a href="{{ route('issues.index') }}">Saját hibáim</a>
                    <a href="{{ route('appointments.index') }}">Időpontjaim</a>
                    <a href="{{ route('sales.index') }}">Market</a>
                @endif
            </div>

            <div class="nav-auth">
                @auth
                    <span class="nav-user">{{ auth()->user()->name }}</span>

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
        const toggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('#main-nav');

        if (toggle && nav) {
            toggle.addEventListener('click', () => nav.classList.toggle('open'));
        }
    </script>
</body>
</html>