<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akciók - Autóalkatrész Webáruház</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/data.js') }}" defer></script>
    <script src="{{ asset('js/script-akciok.js') }}" defer></script>
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
            <li><a href="/promotions" class="active">Akciók</a></li>
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
    <section class="page-header">
        <h1>Akciós Szolgáltatásaink</h1>
        <p>Ne hagyd ki a legjobb ajánlatokat! Korlátozott időtartamig.</p>
        <div class="sale-badge">AKCIÓS HÉT</div>
    </section>

    {{-- ✅ DB-ből jövő akciók listája --}}
    <section class="sale-categories">
        <div class="sale-category">
            <h2>Elérhető akciók</h2>

            @foreach ($promotions as $p)
                <ul>
                    <h4>
                        <li>
                            <strong>{{ $p->title }}</strong>
                            — {{ $p->discount_percent }}%
                            <small>({{ $p->valid_until }})</small>
                        </li>
                    </h4>
                    @if(!empty($p->description))
                        <p>{{ $p->description }}</p>
                    @endif
                </ul>
                <hr>
            @endforeach
        </div>

        {{-- A régi statikus blokkokat meghagyhatod, ez “közel végleges” --}}
        <div class="sale-category">
            <h2><span class="discount-badge-large">-25%</span>Motor alkatrészek</h2>
            <div class="products-grid" id="motor-sale"></div>
            <ul>
                <h4><li>Olajcsere csomag (olaj + szűrő ellenőrzés)</li></h4>
                <h4><li>Légszűrő és pollenszűrő csere</li></h4>
                <h4><li>Gyújtógyertyák ellenőrzése és cseréje</li></h4>
                <h4><li>Motor állapotfelmérés</li></h4>
                <h4><li>Alap karbantartási átvizsgálás</li></h4>
            </ul>
        </div>

        <div class="sale-category">
            <h2><span class="discount-badge-large">-30%</span>Kipufogó rendszer</h2>
            <div class="products-grid" id="fek-sale"></div>
            <ul>
                <h4><li>Kipufogó rendszer átvizsgálás</li></h4>
                <h4><li>Katalizátor ellenőrzés</li></h4>
                <h4><li>Lambdaszonda vizsgálat</li></h4>
                <h4><li>Rögzítések és tömítések ellenőrzése</li></h4>
                <h4><li>Zajszint ellenőrzés</li></h4>
            </ul>
        </div>

        <div class="sale-category">
            <h2><span class="discount-badge-large">-20%</span>Szerviz csomagok</h2>
            <div class="products-grid" id="kipufogo-sale"></div>
            <ul>
                <h4><li>Alap szervizcsomag</li></h4>
                <h4><li>Időszakos karbantartási csomag</li></h4>
                <h4><li>Teljes állapotfelmérés</li></h4>
                <h4><li>Felkészítés műszaki vizsgára</li></h4>
                <h4><li>Szezonális átvizsgálás</li></h4>
            </ul>
        </div>
    </section>

    <section class="special-offer">
        <div class="offer-banner">
            <h3>✨ EXTRA AJÁNLAT ✨</h3>
            <p>Foglalj valamely szervizünkben időpontot és nyerj egy INGYENES olajcserét!</p>
            <small>Akció érvényes: 2026.11.01-2026.11.30</small>
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
