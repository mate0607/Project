<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autonex</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
                    <a href="{{ route('appointments.index') }}">Időpontok kezelése</a>
                @endif

                @if(auth()->check() && auth()->user()->role === 'user')
                    <a href="{{ route('user.dashboard') }}">Dashboard</a>
                    <a href="{{ route('appointments.create') }}">Időpont foglalás</a>
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

    <div class="main-container">
        @yield('content')
    </div>

    <script>
        const toggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('#main-nav');

        if (toggle && nav) {
            toggle.addEventListener('click', () => nav.classList.toggle('open'));
        }
    </script>
</body>
</html>