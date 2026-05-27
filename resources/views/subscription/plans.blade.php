<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piani di Abbonamento</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0f;
            --surface: #13131a;
            --surface-2: #1c1c26;
            --border: rgba(255,255,255,0.06);
            --text: #f0f0f5;
            --text-muted: #7070a0;
            --accent: #6c63ff;
            --accent-2: #ff6b6b;
            --success: #00d97e;
            --gold: #ffd666;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background mesh */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(108,99,255,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(255,107,107,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(0,217,126,0.04) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 64px;
            animation: fadeUp 0.6s ease both;
        }

        .badge-label {
            display: inline-block;
            background: rgba(108,99,255,0.15);
            border: 1px solid rgba(108,99,255,0.3);
            color: #a89fff;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 24px;
        }

        .header h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(36px, 5vw, 56px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .header h1 span {
            background: linear-gradient(135deg, #6c63ff, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: var(--text-muted);
            font-size: 17px;
            font-weight: 300;
            max-width: 480px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Alert */
        .alert {
            background: rgba(0,217,126,0.1);
            border: 1px solid rgba(0,217,126,0.3);
            color: #00d97e;
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 32px;
            text-align: center;
            font-size: 14px;
            animation: fadeUp 0.4s ease both;
        }

        /* Plans grid */
        .plans {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            animation: fadeUp 0.7s ease 0.1s both;
        }

        @media (max-width: 640px) {
            .plans { grid-template-columns: 1fr; }
        }

        .plan-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px 36px;
            position: relative;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-4px);
        }

        .plan-card.active-free {
            border-color: rgba(108,99,255,0.4);
            box-shadow: 0 0 40px rgba(108,99,255,0.08);
        }

        .plan-card.active-pro {
            border-color: rgba(0,217,126,0.4);
            box-shadow: 0 0 40px rgba(0,217,126,0.08);
        }

        .plan-card.featured {
            background: linear-gradient(145deg, #16162a, #1a1a2e);
        }

        /* Pro badge */
        .pro-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #6c63ff, #ff6b6b);
            color: white;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 100px;
            white-space: nowrap;
        }

        .plan-name {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .plan-price {
            font-family: 'Syne', sans-serif;
            font-size: 52px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 8px;
        }

        .plan-price .currency {
            font-size: 24px;
            font-weight: 600;
            vertical-align: super;
            margin-right: 2px;
        }

        .plan-price .period {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-muted);
            font-family: 'DM Sans', sans-serif;
        }

        .plan-desc {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 28px;
        }

        /* Features */
        .features {
            list-style: none;
            margin-bottom: 36px;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            padding: 8px 0;
            color: var(--text);
        }

        .features li.disabled {
            color: var(--text-muted);
            text-decoration: line-through;
            opacity: 0.5;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .feature-icon.yes {
            background: rgba(0,217,126,0.15);
            color: var(--success);
        }

        .feature-icon.no {
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
        }

        /* Buttons */
        .btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-current {
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            cursor: default;
            border: 1px solid var(--border);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text);
        }

        .btn-pro {
            background: linear-gradient(135deg, #6c63ff, #9b6fff);
            color: white;
            box-shadow: 0 8px 24px rgba(108,99,255,0.3);
        }

        .btn-pro:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(108,99,255,0.4);
        }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 48px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
            animation: fadeUp 0.8s ease 0.2s both;
        }

        .back-link:hover { color: var(--text); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="badge-label">Abbonamento</div>
            <h1>Scegli il piano<br><span>giusto per te</span></h1>
            <p>Inizia gratis e passa al Pro quando sei pronto a scalare il tuo business.</p>
        </div>

        @if(session('success'))
            <div class="alert">✓ {{ session('success') }}</div>
        @endif

        <div class="plans">

            {{-- Piano Free --}}
            <div class="plan-card {{ $onFreePlan ? 'active-free' : '' }}">
                <div class="plan-name">Free</div>
                <div class="plan-price">
                    <span class="currency">€</span>0
                    <span class="period">/mese</span>
                </div>
                <p class="plan-desc">Per iniziare a gestire i tuoi progetti senza impegno.</p>
                <div class="divider"></div>
                <ul class="features">
                    <li>
                        <span class="feature-icon yes">✓</span>
                        1 Team
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Max 3 Progetti
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Max 10 Task
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Max 3 membri nel team
                    </li>
                    <li class="disabled">
                        <span class="feature-icon no">✕</span>
                        Fatture
                    </li>
                    <li class="disabled">
                        <span class="feature-icon no">✕</span>
                        Supporto prioritario
                    </li>
                </ul>
                @if($onFreePlan)
                    <button class="btn btn-current" disabled>Piano attuale</button>
                @else
                    <form method="POST" action="{{ route('subscription.cancel') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline">Torna al Free</button>
                    </form>
                @endif
            </div>

            {{-- Piano Pro --}}
            <div class="plan-card featured {{ $onProPlan ? 'active-pro' : '' }}">
                <div class="pro-badge">⭐ Più popolare</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price">
                    <span class="currency">€</span>29
                    <span class="period">/mese</span>
                </div>
                <p class="plan-desc">Per team in crescita che vogliono il massimo dalla piattaforma.</p>
                <div class="divider"></div>
                <ul class="features">
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Team illimitati
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Progetti illimitati
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Task illimitati
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Membri illimitati
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Fatture
                    </li>
                    <li>
                        <span class="feature-icon yes">✓</span>
                        Supporto prioritario
                    </li>
                </ul>
                @if($onProPlan)
                    <button class="btn btn-current" disabled>Piano attuale</button>
                @else
                    <form method="POST" action="{{ route('subscription.checkout') }}">
                        @csrf
                        <button type="submit" class="btn btn-pro">Passa al Pro →</button>
                    </form>
                @endif
            </div>

        </div>

        <a href="/admin" class="back-link">
            ← Torna al pannello
        </a>

    </div>
</body>
</html>