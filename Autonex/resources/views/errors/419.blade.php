<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 – Autonex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <script>if(localStorage.getItem('autonex-theme')==='light')document.documentElement.classList.add('light-mode');</script>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(to bottom, #18191f 0%, #1a1b24 20%, #141520 40%, #111218 100%); font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }
        html.light-mode body { background: linear-gradient(to bottom, #f8fafc, #f1f5f9); }
        .error-page {
            text-align: center;
            padding: 48px 24px;
            max-width: 520px;
            animation: fadeInUp 0.55s ease both;
        }
        .error-code {
            font-family: 'Manrope', 'Inter', sans-serif;
            font-size: clamp(80px, 15vw, 140px);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1;
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        .error-title {
            font-family: 'Manrope', 'Inter', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #f1f5f9;
            margin: 16px 0 12px;
        }
        .error-desc {
            font-size: 15px;
            line-height: 1.65;
            color: #94a3b8;
            margin: 0 0 32px;
        }
        .error-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
            margin-bottom: 24px;
        }
        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .error-btn-primary {
            background: linear-gradient(135deg, #5588ff, #3d6be6);
            color: #fff;
            box-shadow: 0 4px 16px rgba(85, 136, 255, 0.3);
        }
        .error-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(85, 136, 255, 0.4);
            color: #fff;
        }
        .error-btn-outline {
            background: transparent;
            color: #cbd5e1;
            border: 1.5px solid rgba(148, 163, 184, 0.25);
        }
        .error-btn-outline:hover {
            border-color: rgba(85, 136, 255, 0.4);
            color: #f1f5f9;
            background: rgba(85, 136, 255, 0.06);
            transform: translateY(-2px);
        }
        .error-bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: -1;
        }
        .error-bg-orb-1 {
            width: 400px; height: 400px;
            background: rgba(139, 92, 246, 0.06);
            top: -10%; left: -5%;
        }
        .error-bg-orb-2 {
            width: 300px; height: 300px;
            background: rgba(85, 136, 255, 0.06);
            bottom: -10%; right: -5%;
        }

        html.light-mode .error-title { color: #1e293b; }
        html.light-mode .error-desc { color: #64748b; }
        html.light-mode .error-btn-outline {
            color: #475569;
            border-color: rgba(0, 0, 0, 0.12);
        }
        html.light-mode .error-btn-outline:hover {
            color: #3d6be6;
            border-color: rgba(85, 136, 255, 0.35);
            background: rgba(85, 136, 255, 0.04);
        }
        html.light-mode .error-icon {
            background: rgba(139, 92, 246, 0.07);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="error-bg-orb error-bg-orb-1"></div>
    <div class="error-bg-orb error-bg-orb-2"></div>

    <div class="error-page">
        <div class="error-icon">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <h1 class="error-code">419</h1>
        <h2 class="error-title">Munkamenet lejárt</h2>
        <p class="error-desc">A munkamenet biztonsági okokból lejárt. Kérjük, frissítsd az oldalt és próbáld újra.</p>
        <div class="error-actions">
            <a href="javascript:location.reload()" class="error-btn error-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Oldal frissítése
            </a>
            <a href="{{ url('/') }}" class="error-btn error-btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1m-2 0h2"/></svg>
                Főoldal
            </a>
        </div>
    </div>
</body>
</html>
