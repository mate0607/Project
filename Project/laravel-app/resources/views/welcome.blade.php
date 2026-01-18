<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autóalkatrész- és szervizajánló webhely</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/data.js') }}" defer></script>
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
                <li><a href="/" class="active">Főoldal</a></li>
                <li><a href="/promotions">Akciók</a></li>
                <li><a href="/services">Szolgáltatások</a></li>
                <li><a href="/garages">Szervizek</a></li>
            </ul>
            <div class="search-box">
                <input type="text" placeholder="Keresés...">
                <button>🔍</button>
            </div>
        </div>
    </nav>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h2>Segitünk bármiben!</h2>
                <a href="#" class="cta-button">Vedd fel velünk a kapcsolatot!</a>
            </div>
        </section>

        <section class="weekly-deals">
            <div class="section-header">
                <h2>Szolgáltatásaink</h2>
            </div>

            <div class="container">
                <div class="special-deals">
                    <ul>
                        <li>
                            <h3><strong>Alkatrész-ajánlás autóadatok alapján</strong><br></h3>
                            <h4>
                                A felhasználó megadja autója márkáját, modelljét és évjáratát, a rendszer pedig
                                csak az adott járműhöz kompatibilis alkatrészeket jeleníti meg.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Probléma-alapú keresés</strong><br></h3>
                            <h4>
                                Lehetőség van gyakori autós problémák kiválasztására (pl. fékzaj, indítási
                                problémák, olajfogyasztás), amelyekhez a rendszer javasolt megoldásokat kínál.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Megbízható gyártók bemutatása</strong><br></h3>
                            <h4>
                                Csak ellenőrzött, ismert márkák alkatrészei szerepelnek az oldalon, rövid
                                leírással és ajánlással, hogy a felhasználó könnyebben dönthessen.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Szervizszolgáltatások ajánlása</strong><br></h3>
                            <h4>
                                Az oldal javaslatot ad arra, hogy egy adott alkatrész cseréjéhez milyen
                                szervizszolgáltatás szükséges, valamint milyen csomagok érhetők el.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Időpontfoglalás szervizhez</strong><br></h3>
                            <h4>
                                Egyszerű, online űrlapon keresztül lehet szervizidőpontot foglalni, amely
                                JavaScript alapú validációval ellenőrzi a megadott adatokat.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Tájékoztató árak és karbantartási információk</strong><br></h3>
                            <h4>
                                Az alkatrészek mellett megjelennek tájékoztató árak és javasolt
                                csereperiódusok, amelyek segítenek a karbantartás megtervezésében.
                            </h4>
                        </li>

                        <li>
                            <h3><strong>Reszponzív megjelenés</strong><br></h3>
                            <h4>
                                Az alkalmazás asztali és mobil eszközökön egyaránt használható, a felület
                                alkalmazkodik a különböző képernyőméretekhez.
                            </h4>
                        </li>

                    </ul>
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
                <p>&copy; 2026 Autóalkatrész- és szervizajánló webhely. Minden jog fenntartva.</p>
            </div>
        </div>
    </footer>

    {{-- Bejelentkezési modális ablak --}}
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
                        <div class="error-message" id="login-email-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Jelszó</label>
                        <input type="password" id="login-password" name="password" required>
                        <div class="error-message" id="login-password-error"></div>
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
                    <div class="auth-divider">
                        <span>vagy</span>
                    </div>
                    <button type="button" class="btn btn-outline btn-full google-login">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Bejelentkezés Google-lal
                    </button>
                </form>

                <form id="register-form" class="auth-form">
                    <h2>Regisztráció</h2>
                    <div class="form-group">
                        <label for="register-name">Teljes név</label>
                        <input type="text" id="register-name" name="name" required>
                        <div class="error-message" id="register-name-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="register-email">E-mail cím</label>
                        <input type="email" id="register-email" name="email" required>
                        <div class="error-message" id="register-email-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Jelszó</label>
                        <input type="password" id="register-password" name="password" required>
                        <div class="error-message" id="register-password-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm-password">Jelszó megerősítése</label>
                        <input type="password" id="register-confirm-password" name="confirmPassword" required>
                        <div class="error-message" id="register-confirm-password-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="accept-terms" required>
                            <span class="checkmark"></span>
                            Elfogadom a <a href="#" class="link">felhasználási feltételeket</a>
                        </label>
                        <div class="error-message" id="terms-error"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Regisztráció</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Elfelejtett jelszó modal --}}
    <div id="forgot-password-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-forgot">&times;</span>
            <div class="auth-container">
                <h2>Elfelejtett jelszó</h2>
                <p>Add meg az e-mail címed, és küldünk egy linket a jelszó visszaállításához.</p>
                <form id="forgot-password-form" class="auth-form">
                    <div class="form-group">
                        <label for="forgot-email">E-mail cím</label>
                        <input type="email" id="forgot-email" name="email" required>
                        <div class="error-message" id="forgot-email-error"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Küldés</button>
                </form>
                <div class="auth-footer">
                    <a href="#" class="back-to-login">Vissza a bejelentkezéshez</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Felhasználói menü (bejelentkezés után) --}}
    <div id="user-menu" class="user-menu" style="display: none;">
        <div class="user-info">
            <div class="user-avatar">
                <span id="user-avatar">U</span>
            </div>
            <div class="user-details">
                <span id="user-name" class="user-name">Felhasználó</span>
                <span id="user-email" class="user-email">user@example.com</span>
            </div>
        </div>
        <div class="user-menu-items">
            <a href="#" class="user-menu-item">Profilom</a>
            <a href="#" class="user-menu-item">Rendeléseim</a>
            <a href="#" class="user-menu-item">Kedvencek</a>
            <div class="user-menu-divider"></div>
            <button id="logout-btn" class="user-menu-item logout">Kijelentkezés</button>
        </div>
    </div>


</body>

</html>
