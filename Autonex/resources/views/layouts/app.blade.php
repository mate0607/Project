<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autonex</title>
    @php
        $appCssVersion = @filemtime(public_path('css/app.css')) ?: 1;
        $lightCssVersion = @filemtime(public_path('css/light-mode.css')) ?: 1;
        $adminCssVersion = @filemtime(public_path('css/admin.css')) ?: 1;
    @endphp
    <link rel="icon" type="image/png" href="{{ asset('engine.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ $appCssVersion }}">
    <link rel="stylesheet" href="{{ asset('css/light-mode.css') }}?v={{ $lightCssVersion }}">
    @if(auth()->check() && auth()->user()->role === 'admin')
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ $adminCssVersion }}">
    @endif
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
                    <a href="{{ route('admin.users.index') }}">Felhasználók</a>
                    <a href="{{ route('admin.messages.index') }}" style="position:relative;">Üzenetek
                        @if($adminUnreadMsgCount > 0)
                            <span style="position:absolute;top:-6px;right:-12px;background:#ef4444;color:#fff;font-size:11px;font-weight:700;min-width:18px;height:18px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;line-height:1;">{{ $adminUnreadMsgCount > 9 ? '9+' : $adminUnreadMsgCount }}</span>
                        @endif
                    </a>
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
                    {{-- Notification bell for standard users --}}
                    @if($isStandardUser)
                    <div class="nav-notif-wrap">
                        <button type="button" class="nav-notif-btn" id="notifToggle" aria-label="Értesítések">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            @if($navUnreadCount > 0)
                                <span class="nav-notif-badge">{{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}</span>
                            @endif
                        </button>
                        <div class="nav-notif-dropdown" id="notifDropdown">
                            <div class="nav-notif-header">
                                <span class="nav-notif-title">Értesítések</span>
                                <div class="nav-notif-header-actions">
                                    @if($navUnreadCount > 0)
                                        <button type="button" class="nav-notif-read-all" id="notifReadAll">Összes olvasott</button>
                                        <span class="nav-notif-count">{{ $navUnreadCount }} új</span>
                                    @endif
                                </div>
                            </div>
                            <div class="nav-notif-list">
                                @forelse($navNotifications as $notif)
                                    <div class="nav-notif-item {{ !$notif->is_read ? 'nav-notif-unread' : '' }}" data-notif-id="{{ $notif->id }}">
                                        <div class="nav-notif-dot-wrap">
                                            @if(!$notif->is_read)
                                                <span class="nav-notif-dot"></span>
                                            @else
                                                <span class="nav-notif-dot nav-notif-dot--read"></span>
                                            @endif
                                        </div>
                                        <div class="nav-notif-body">
                                            <strong>{{ $notif->title }}</strong>
                                            <span>{{ Str::limit($notif->message, 70) }}</span>
                                            <time>{{ $notif->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nav-notif-empty">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        <span>Nincs értesítésed</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @endif

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
        @if(session('success'))
            <div class="alert-success" style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);color:#4ade80;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:.95rem;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:.95rem;">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>

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
                // Close notification dropdown when profile opens
                const nd = document.getElementById('notifDropdown');
                if (nd) nd.classList.remove('nav-notif-open');
            });
        }

        // Notification dropdown toggle
        const notifToggle = document.getElementById('notifToggle');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifToggle && notifDropdown) {
            notifToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('nav-notif-open');
                // Close profile dropdown when notifications open
                if (profileDropdown) profileDropdown.classList.remove('nav-dd-open');
            });

            notifDropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Close both dropdowns when clicking outside
        document.addEventListener('click', () => {
            if (profileDropdown) profileDropdown.classList.remove('nav-dd-open');
            if (notifDropdown) notifDropdown.classList.remove('nav-notif-open');
        });

        // Mark all notifications as read
        const readAllBtn = document.getElementById('notifReadAll');
        if (readAllBtn) {
            readAllBtn.addEventListener('click', function () {
                fetch('{{ route("notifications.read-all") }}', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    document.querySelectorAll('.nav-notif-item.nav-notif-unread').forEach(el => {
                        el.classList.remove('nav-notif-unread');
                        const dot = el.querySelector('.nav-notif-dot');
                        if (dot) dot.classList.add('nav-notif-dot--read');
                    });
                    const badge = document.querySelector('.nav-notif-badge');
                    const countEl = document.querySelector('.nav-notif-count');
                    if (badge) badge.remove();
                    if (countEl) countEl.remove();
                    this.remove();
                });
            });
        }

        // Mark notification as read on hover
        document.querySelectorAll('.nav-notif-item.nav-notif-unread').forEach(item => {
            item.addEventListener('mouseenter', function () {
                const id = this.dataset.notifId;
                if (!id || this.dataset.reading) return;
                this.dataset.reading = '1';
                fetch('{{ route("notifications.read", ":id") }}'.replace(':id', id), {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    this.classList.remove('nav-notif-unread');
                    const dot = this.querySelector('.nav-notif-dot');
                    if (dot) dot.classList.add('nav-notif-dot--read');
                    // Update badge count
                    const badge = document.querySelector('.nav-notif-badge');
                    const countEl = document.querySelector('.nav-notif-count');
                    if (badge) {
                        let n = parseInt(badge.textContent) || 0;
                        n = Math.max(0, n - 1);
                        if (n === 0) { badge.remove(); if (countEl) countEl.remove(); }
                        else { badge.textContent = n > 9 ? '9+' : n; if (countEl) countEl.textContent = n + ' új'; }
                    }
                });
            }, { once: true });
        });

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