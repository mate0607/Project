<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szervizek - Autóalkatrész Webáruház</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/data.js') }}" defer></script>
    <script src="{{ asset('js/script-kapcsolat.js') }}" defer></script>
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
                <li><a href="/services">Szolgáltatások</a></li>
                <li><a href="/garages" class="active">Szervizek</a></li>
            </ul>
            <div class="search-box">
                <input type="text" placeholder="Keresés...">
                <button>🔍</button>
            </div>
        </div>
    </nav>

    <main class="container">
        <section class="page-header">
            <h1>Szervizek</h1>
            <p>Válassz megbízható szervizeink közül – elérhetőségek és leírások egy helyen.</p>
        </section>

        {{-- ✅ DB-s szervizek lista --}}
        <section class="contact-content">
            <div class="contact-info-section">
                <div class="contact-card">
                    <div class="contact-icon">🏁</div>
                    <h3>Szervizek </h3>

                    @foreach ($garages as $g)
                        <p style="margin: 10px 0;">
                            <strong>{{ $g->name }}</strong><br>
                            {{ $g->address }}<br>

                            @if(!empty($g->phone))
                                📞 {{ $g->phone }}<br>
                            @endif

                            @if(!empty($g->email))
                                ✉️ {{ $g->email }}<br>
                            @endif

                            @if(!empty($g->description))
                                <small>{{ $g->description }}</small>
                            @endif
                        </p>
                        <hr>
                    @endforeach
                </div>

                {{-- Meghagyjuk a “kapcsolat” kártyákat, mert jól néznek ki --}}
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3>Központi címünk</h3>
                    <p>1117 Budapest, Alkotás utca 12.</p>
                    <p>Magyarország</p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <h3>Telefon</h3>
                    <p>+36 1 234 5678</p>
                    <p>H-P: 8:00-17:00</p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <h3>E-mail</h3>
                    <p>info@autoparts.hu</p>
                    <p>ugyfelszolgalat@autoparts.hu</p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">🕒</div>
                    <h3>Nyitvatartás</h3>
                    <p><strong>Hétfő-Péntek:</strong> 8:00-17:00</p>
                    <p><strong>Szombat:</strong> 9:00-13:00</p>
                    <p><strong>Vasárnap:</strong> Zárva</p>
                </div>
            </div>

            {{-- Ezt a form részt is megtartjuk, “közel végleges” frontendnek jó --}}
            <div class="contact-form-section">
                <div class="contact-form">
                    <h2>Küldj üzenetet</h2>
                    <form id="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name">Név *</label>
                                <input type="text" id="contact-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email">E-mail *</label>
                                <input type="email" id="contact-email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject">Tárgy *</label>
                            <input type="text" id="contact-subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-message">Üzenet *</label>
                            <textarea id="contact-message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Üzenet küldése</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="map-section">
            <h2>Megközelíthetőség</h2>
            <div class="map-container">
                <div class="map-placeholder">
                    <div class="map-content">
                        <h3>📍 Autóalkatrész Webáruház</h3>
                        <p>1117 Budapest, Alkotás utca 12.</p>
                        <div class="transport-info">
                            <div class="transport-option">
                                <strong>Busz:</strong> 7, 7A, 107
                            </div>
                            <div class="transport-option">
                                <strong>Villamos:</strong> 4, 6
                            </div>
                            <div class="transport-option">
                                <strong>Metró:</strong> M4 - Újbuda-központ
                            </div>
                        </div>
                        <div class="parking-info">
                            <strong>Parkolás:</strong> Ingyenes parkoló az épület mögött
                        </div>
                    </div>
                </div>
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

</body>

</html>
