<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időpont visszaigazolás – AutoNex</title>
    <style>{!! file_get_contents(public_path('css/appointment-confirmation-mail.css')) !!}</style>
</head>
<body class="ac-body">
    <div class="ac-preheader">
        AutoNex – Az időpontod sikeresen rögzítésre került.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="ac-wrapper">
        <tr>
            <td align="center" class="ac-wrapper-cell">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="ac-container">

                    {{-- Header --}}
                    <tr>
                        <td class="ac-header">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="ac-logo">AutoNex</td>
                                </tr>
                                <tr>
                                    <td class="ac-subtitle">Időpont visszaigazolás</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td class="ac-content">
                            <p class="ac-greeting">Kedves {{ $userName }}!</p>
                            <p>
                                Az időpontodat sikeresen rögzítettük az <strong>AutoNex</strong> rendszerben.
                                Az alábbiakban találod a foglalás részleteit.
                            </p>

                            {{-- Appointment details --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="ac-info-box">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="ac-info-title">Foglalás részletei</td>
                                            </tr>
                                            <tr>
                                                <td class="ac-info-item">
                                                    <span class="ac-info-icon">&#x1F4C5;</span>
                                                    Dátum: <span class="ac-info-value">{{ $date }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ac-info-item">
                                                    <span class="ac-info-icon">&#x1F552;</span>
                                                    Időpont: <span class="ac-info-value">{{ $time }}</span>
                                                </td>
                                            </tr>
                                            @if($service)
                                            <tr>
                                                <td class="ac-info-item">
                                                    <span class="ac-info-icon">&#x1F527;</span>
                                                    Szolgáltatás: <span class="ac-info-value">{{ $service }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($car)
                                            <tr>
                                                <td class="ac-info-item">
                                                    <span class="ac-info-icon">&#x1F697;</span>
                                                    Jármű: <span class="ac-info-value">{{ $car->make_model }}</span>
                                                </td>
                                            </tr>
                                                @if($car->license_plate)
                                                <tr>
                                                    <td class="ac-info-item">
                                                        <span class="ac-info-icon">&#x2022;</span>
                                                        Rendszám: <span class="ac-info-value">{{ $car->license_plate }}</span>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                            <tr>
                                                <td class="ac-info-item">
                                                    <span class="ac-info-icon">&#x1F4CB;</span>
                                                    Munkalap szám: <span class="ac-info-value">{{ $workNumber }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p class="ac-text-muted" style="margin-top: 20px;">
                                Kérjük, az időpont előtt érkezz meg időben. Amennyiben módosítani
                                vagy lemondani szükséges, kérjük, vedd fel velünk a kapcsolatot.
                            </p>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td class="ac-divider"></td></tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="ac-footer">
                            <p class="ac-footer-note">
                                Ez egy automatikusan generált üzenet.<br>
                                Kérjük, erre az emailre ne válaszolj.
                            </p>
                            <p class="ac-footer-copy">
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
