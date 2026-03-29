<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üdvözlünk az AutoNex-ben</title>
    <style>{!! file_get_contents(public_path('css/welcome-mail.css')) !!}</style>
</head>
<body class="wm-body">
    <div class="wm-preheader">
        AutoNex – A fiókod sikeresen létrejött.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="wm-wrapper">
        <tr>
            <td align="center" class="wm-wrapper-cell">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="wm-container">

                    {{-- Header --}}
                    <tr>
                        <td class="wm-header">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="wm-logo">AutoNex</td>
                                </tr>
                                <tr>
                                    <td class="wm-subtitle">Járműkezelés egyszerűen</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td class="wm-content">
                            <p class="wm-greeting">Szia {{ $userName }}!</p>
                            <p>
                                Örülünk, hogy csatlakoztál! A fiókodat sikeresen létrehoztuk
                                az <strong>AutoNex</strong> rendszerben.
                            </p>
                            <p class="wm-text-muted">
                                Mostantól hozzáférsz a teljes platformhoz – kezelheted
                                járműveidet, időpontot foglalhatsz, és nyomon követheted
                                a szervizfolyamatokat.
                            </p>

                            {{-- Info box --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="wm-info-box">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="wm-info-title">Elérhető funkciók</td>
                                            </tr>
                                            <tr>
                                                <td class="wm-info-item">
                                                    <span class="wm-info-icon">&#x2022;</span> Járművek kezelése és nyilvántartása
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="wm-info-item">
                                                    <span class="wm-info-icon">&#x2022;</span> Szerviz időpontok foglalása
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="wm-info-item">
                                                    <span class="wm-info-icon">&#x2022;</span> Szervizfolyamatok nyomon követése
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td class="wm-divider"></td></tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="wm-footer">
                            <p class="wm-footer-note">
                                Ez egy automatikusan generált üzenet.<br>
                                Kérjük, erre az emailre ne válaszolj.
                            </p>
                            <p class="wm-footer-copy">
                                &copy; {{ date('Y') }} AutoNex. Minden jog fenntartva.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>