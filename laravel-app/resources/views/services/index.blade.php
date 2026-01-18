<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szerviz - Autóalkatrész Webáruház</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/data.js') }}" defer></script>
    <script src="{{ asset('js/script-szerviz.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ asset('js/simple-auth.js') }}" defer></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>AUTO<span>PARTS</span></h1>
                <p>Minden, amire az autódnak szüksége van</p>
            </div>
            <div class="user-actions">
                <button id="login-btn" class="btn btn-outline btn-sm">
                    <span id="login-btn-text">Bejelentkezés</span>
                </button>
            </div>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul class="main-menu">
                <li><a href="/">Főoldal</a></li>
                <li><a href="/promotions">Akciók</a></li>
                <li><a href="/services" class="active">Szolgáltatások</a></li>
                <li><a href="/garages">Szervizek</a></li>
            </ul>
            <div class="search-box">
                <input type="text" placeholder="Keresés...">
                <button>🔍</button>
            </div>
        </div>
    </nav>

    <main class="container">
        <section class="page-header">
            <h1>Professzionális Autószerviz</h1>
            <p>Minőségi szervizelés és javítás szakértő munkatársainkkal</p>
        </section>

        {{-- ✅ DB-ből jövő szolgáltatások --}}
        <section class="services-overview">
            <div class="section-header">
                <h2>Szolgáltatásaink</h2>
            </div>

            {{-- Ha a CSS nem formázza, ez akkor is biztosan látszik --}}
            <div style="background:#fff; padding:16px; border-radius:8px; margin-bottom:20px;">
                @foreach ($services as $s)
                    <p>
                        <b>{{ $s->name }}</b>
                        – {{ $s->price }} Ft ({{ $s->duration_minutes }} perc)
                    </p>
                    @if(!empty($s->description))
                        <small>{{ $s->description }}</small>
                    @endif
                    <hr>
                @endforeach
            </div>

            <div class="section-header">
                <h2>Szervizeink</h2>
            </div>

            {{-- A régi statikus “szervizek” blokkot meghagyjuk, mert közel végleges design --}}
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>CityCar Szerviz</h3>
                    <p>Budapest, XIII. kerület – Váci út 120.</p>
                    <ul>
                        <li>Alap- és nagy szerviz</li>
                        <li>Olajcsere, fék- és futómű javítás</li>
                        <li>Műszaki vizsga előkészítés</li>
                        <li>Gyors diagnosztikai átvizsgálás</li>
                    </ul>
                    <div class="service-price">+36 30 123 4567</div>
                </div>

                <div class="service-card">
                    <div class="service-icon">⚙️</div>
                    <h3>ProAuto Javító</h3>
                    <p>Budapest – Kassai út 45.</p>
                    <ul>
                        <li>Teljes körű autószerviz</li>
                        <li>Motor- és elektronikai diagnosztika</li>
                        <li>Futómű beállítás</li>
                        <li>Klíma töltés és javítás</li>
                    </ul>
                    <div class="service-price">+36 20 987 6543</div>
                </div>

                <div class="service-card">
                    <div class="service-icon">🚗</div>
                    <h3>Fix&Go Autószerviz</h3>
                    <p>Budapest – Kálvária sugárút 89.</p>
                    <ul>
                        <li>Gyorsszerviz és időszakos karbantartás</li>
                        <li>Fék- és lengéscsillapító csere</li>
                        <li>Hibakód olvasás</li>
                        <li>Teljesítményvizsgálat</li>
                    </ul>
                    <div class="service-price">+36 70 555 1122</div>
                </div>
            </div>
        </section>

        <section class="appointment-section">
            <div class="appointment-form">
                <h2>Időpontfoglalás</h2>
                <form id="appointment-form">
                    <div class="form-group">
                        <label for="name">Név *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefon *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="car-type">Autó típusa *</label>
                        <input type="text" id="car-type" name="car-type" required>
                    </div>
                    <div class="form-group">
                        <label for="service-type">Szerviz típusa *</label>
                        <select id="service-type" name="service-type" required>
                            <option value="">Válassz szerviztípust</option>
                            <option value="basic">Alap szerviz</option>
                            <option value="full">Nagy szerviz</option>
                            <option value="diagnostic">Diagnosztika</option>
                            <option value="repair">Javítás</option>
                            <option value="other">Egyéb</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Előnyben részesített dátum *</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Egyéb megjegyzés</label>
                        <textarea id="message" name="message" rows="4"></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Időpontfoglalás</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Információk</h3>
                    <ul>
                        <li><a href="#">ÁSZF</a></li>
                        <li><a href="#">Adatvédelmi nyilatkozat</a></li>
                        <li><a href="#">Szállítási információk</a></li>
                        <li><a href="#">Garancia</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Segítség</h3>
                    <ul>
                        <li><a href="#">GYIK</a></li>
                        <li><a href="#">Vásárlási útmutató</a></li>
                        <li><a href="#">Rendelés követése</a></li>
                        <li><a href="#">Cserék és visszatérítések</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kapcsolat</h3>
                    <p>📍 1117 Budapest, Alkotás utca 12.</p>
                    <p>📞 +36 1 234 5678</p>
                    <p>✉️ info@autoparts.hu</p>
                    <p>🕒 H-P: 8:00-17:00</p>
                </div>
                <div class="footer-section">
                    <h3>Hírlevél</h3>
                    <p>Iratkozz fel hírlevelünkre, hogy értesülj legújabb akcióinkról!</p>
                    <div class="newsletter">
                        <input type="email" placeholder="E-mail címed">
                        <button>Feliratkozás</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Autóalkatrész Webáruház. Minden jog fenntartva.</p>
            </div>
        </div>
    </footer>

    {{-- Modálok maradhatnak, most nem kötelező működniük (frontend design) --}}
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-login">&times;</span>
            <div class="auth-container">
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Bejelentkezés</button>
                    <button class="auth-tab" data-tab="register">Regisztráció</button>
                </div>

                <form id="login-form" class="auth-form active">
                    <h2>Bejelentkezés</h2>
                    <div class="form-group">
                        <label for="login-email">E-mail cím</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Jelszó</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember-me">
                            <span class="checkmark"></span>
                            Emlékezz rám
                        </label>
                        <a href="#" class="forgot-password">Elfelejtett jelszó?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Bejelentkezés</button>
                </form>

                <form id="register-form" class="auth-form">
                    <h2>Regisztráció</h2>
                    <div class="form-group">
                        <label for="register-name">Teljes név</label>
                        <input type="text" id="register-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">E-mail cím</label>
                        <input type="email" id="register-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Jelszó</label>
                        <input type="password" id="register-password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm-password">Jelszó megerősítése</label>
                        <input type="password" id="register-confirm-password" name="confirmPassword" required>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="accept-terms" required>
                            <span class="checkmark"></span>
                            Elfogadom a felhasználási feltételeket
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Regisztráció</button>
                </form>
            </div>
        </div>
    </div>


</body>
</html>
