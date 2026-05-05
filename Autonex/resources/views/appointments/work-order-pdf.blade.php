<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Számla – {{ $appointment->work_number ?? ('MNK-' . $appointment->id) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #fff;
            padding: 32px 36px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header-logo {
            font-size: 22px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 1px;
        }
        .header-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .header-right {
            text-align: right;
        }
        .header-right .work-number {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }
        .header-right .work-date {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }

        /* Sections */
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #2563eb;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* Grid */
        .grid-2 {
            display: table;
            width: 100%;
        }
        .grid-2-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .grid-2-col:last-child { padding-right: 0; }

        .info-row {
            margin-bottom: 7px;
        }
        .info-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1px;
        }
        .info-value {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Report block */
        .report-block {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 13px;
            margin-bottom: 10px;
            font-size: 11.5px;
            line-height: 1.6;
            color: #334155;
        }
        .report-block.warning {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }
        .report-block.critical {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
        .report-block-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 5px;
            color: #64748b;
        }
        .report-block.warning .report-block-label { color: #c2410c; }
        .report-block.critical .report-block-label { color: #b91c1c; }

        /* Cost highlight */
        .cost-box {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 6px;
            padding: 12px 16px;
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .cost-label {
            font-size: 10px;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .cost-value {
            font-size: 20px;
            font-weight: 700;
            color: #1d4ed8;
            margin-top: 2px;
        }

        /* Ready badge */
        .ready-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Stage timeline */
        .timeline {
            margin: 8px 0 4px;
        }
        .timeline-item {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .timeline-dot-col {
            display: table-cell;
            width: 18px;
            vertical-align: top;
            padding-top: 2px;
        }
        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #2563eb;
            display: inline-block;
        }
        .timeline-dot.inactive {
            background: #e2e8f0;
        }
        .timeline-text-col {
            display: table-cell;
            vertical-align: top;
            font-size: 11.5px;
            color: #1e293b;
        }
        .timeline-text-col.inactive {
            color: #94a3b8;
        }

        /* Footer */
        .footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 28px;
            padding-top: 12px;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="header-logo">AUTONEX</div>
            <div class="header-sub">Szervizközpont &amp; Gépjárműkezelés</div>
        </div>
        <div class="header-right">
            <div class="work-number">Számla: {{ $appointment->work_number ?? ('MNK-' . $appointment->id) }}</div>
            <div class="work-date">Kiállítva: {{ now()->format('Y.m.d H:i') }}</div>
            <div style="margin-top:6px;">
                <span class="ready-badge">✓ Kész – elvihető</span>
            </div>
        </div>
    </div>

    {{-- Ügyfél & jármű adatok --}}
    <div class="section">
        <div class="section-title">Ügyfél &amp; Jármű adatok</div>
        <div class="grid-2">
            <div class="grid-2-col">
                @if($appointment->user)
                    <div class="info-row">
                        <div class="info-label">Ügyfél neve</div>
                        <div class="info-value">{{ $appointment->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">E-mail</div>
                        <div class="info-value">{{ $appointment->user->email }}</div>
                    </div>
                @elseif($appointment->customer_name)
                    <div class="info-row">
                        <div class="info-label">Ügyfél neve</div>
                        <div class="info-value">{{ $appointment->customer_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telefonszám</div>
                        <div class="info-value">{{ $appointment->customer_phone }}</div>
                    </div>
                @endif
            </div>
            <div class="grid-2-col">
                @if($appointment->car)
                    <div class="info-row">
                        <div class="info-label">Autó</div>
                        <div class="info-value">{{ $appointment->car->make_model }}</div>
                    </div>
                    @if($appointment->car->license_plate)
                        <div class="info-row">
                            <div class="info-label">Rendszám</div>
                            <div class="info-value">{{ $appointment->car->license_plate }}</div>
                        </div>
                    @endif
                    @if($appointment->car->vin)
                        <div class="info-row">
                            <div class="info-label">Alvázszám (VIN)</div>
                            <div class="info-value">{{ $appointment->car->vin }}</div>
                        </div>
                    @endif
                @else
                    <div class="info-row">
                        <div class="info-label">Autó</div>
                        <div class="info-value">{{ $appointment->car_brand }} {{ $appointment->car_model }} ({{ $appointment->car_year }})</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Motor / Üzemanyag</div>
                        <div class="info-value">{{ $appointment->car_engine }} / {{ $appointment->car_fuel_type }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Szerviz adatok --}}
    <div class="section">
        <div class="section-title">Szerviz adatok</div>
        <div class="grid-2">
            <div class="grid-2-col">
                <div class="info-row">
                    <div class="info-label">Szerviz dátuma</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date)->format('Y.m.d') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Időpont</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Szerviz típusa</div>
                    <div class="info-value">{{ $appointment->service ?: 'Általános szerviz' }}</div>
                </div>
            </div>
            <div class="grid-2-col">
                @if($appointment->mechanic_name)
                    <div class="info-row">
                        <div class="info-label">Felelős szerelő</div>
                        <div class="info-value">{{ $appointment->mechanic_name }}</div>
                    </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Állapot</div>
                    <div class="info-value">Kész – elvihető</div>
                </div>
            </div>
        </div>

        @if($appointment->description)
            <div class="info-row" style="margin-top:8px;">
                <div class="info-label">Ügyfél megjegyzés</div>
                <div class="report-block">{{ $appointment->description }}</div>
            </div>
        @endif
    </div>

    {{-- Szerviz eredmény --}}
    <div class="section">
        <div class="section-title">Elvégzett munkák &amp; Eredmény</div>

        @if($appointment->total_cost)
            <div class="cost-box">
                <div class="cost-label">Végösszeg</div>
                <div class="cost-value">{{ number_format($appointment->total_cost, 0, ',', ' ') }} Ft</div>
            </div>
        @endif

        @if($appointment->service_report)
            <div class="report-block">
                <div class="report-block-label">Elvégzett munkák</div>
                {{ $appointment->service_report }}
            </div>
        @endif

        @if($appointment->issues_found)
            <div class="report-block warning">
                <div class="report-block-label">Talált hibák / megjegyzések</div>
                {{ $appointment->issues_found }}
            </div>
        @endif

        @if($appointment->critical_warning)
            <div class="report-block critical">
                <div class="report-block-label">⚠ Kritikus figyelmeztetés</div>
                {{ $appointment->critical_warning }}
            </div>
        @endif
    </div>

    {{-- Szerviz folyamat --}}
    @php
        $stageLabels = [
            'received'    => 'Autó átvéve',
            'inspected'   => 'Állapotfelmérés kész',
            'in_progress' => 'Szerelés / alkatrészre vár',
            'ready'       => 'Kész, elvihető',
        ];
        $stageOrder = ['received', 'inspected', 'in_progress', 'ready'];
        $currentIdx = $appointment->service_stage ? array_search($appointment->service_stage, $stageOrder) : -1;
    @endphp

    <div class="section">
        <div class="section-title">Szerviz folyamat</div>
        <div class="timeline">
            @foreach($stageOrder as $i => $stage)
                @php $done = $i <= $currentIdx; @endphp
                <div class="timeline-item">
                    <div class="timeline-dot-col">
                        <span class="timeline-dot {{ $done ? '' : 'inactive' }}"></span>
                    </div>
                    <div class="timeline-text-col {{ $done ? '' : 'inactive' }}">
                        {{ ($i + 1) }}. {{ $stageLabels[$stage] }}
                        @if($done) ✓ @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Autonex Szervizközpont &bull; Ez a dokumentum elektronikusan generált számla. &bull; {{ now()->format('Y.m.d H:i') }}
    </div>

</body>
</html>
