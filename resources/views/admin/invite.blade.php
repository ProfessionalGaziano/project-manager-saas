<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invita Utente</title>
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
            --success: #00d97e;
            --danger: #ff6b6b;
            --warning: #ffd666;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(108,99,255,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(0,217,126,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 720px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        /* Header */
        .header {
            margin-bottom: 48px;
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
            margin-bottom: 20px;
        }

        .header h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
        }

        .header h1 span {
            background: linear-gradient(135deg, #6c63ff, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 300;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            animation: fadeUp 0.4s ease both;
        }

        .alert-success {
            background: rgba(0,217,126,0.1);
            border: 1px solid rgba(0,217,126,0.3);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.3);
            color: var(--danger);
        }

        .alert ul { padding-left: 16px; margin: 0; }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 24px;
            animation: fadeUp 0.7s ease 0.1s both;
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 28px;
            color: var(--text);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input[type="email"],
        select {
            width: 100%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s ease;
            appearance: none;
        }

        input[type="email"]:focus,
        select:focus {
            border-color: rgba(108,99,255,0.5);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
        }

        input::placeholder { color: var(--text-muted); }

        /* Select arrow */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: '▾';
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            font-size: 12px;
        }

        select option {
            background: var(--surface-2);
            color: var(--text);
        }

        /* Role badges */
        .role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 8px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-label {
            display: block;
            padding: 12px 8px;
            border: 1px solid var(--border);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--surface-2);
        }

        .role-label .role-icon {
            display: block;
            font-size: 20px;
            margin-bottom: 6px;
        }

        .role-label .role-name {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .role-label .role-desc {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 3px;
            opacity: 0.7;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: rgba(108,99,255,0.5);
            background: rgba(108,99,255,0.1);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
        }

        .role-option input[type="radio"]:checked + .role-label .role-name {
            color: #a89fff;
        }

        .role-label:hover {
            border-color: rgba(108,99,255,0.3);
            background: rgba(108,99,255,0.05);
        }

        /* Button */
        .btn-submit {
            width: 100%;
            padding: 15px 24px;
            background: linear-gradient(135deg, #6c63ff, #9b6fff);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 8px 24px rgba(108,99,255,0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(108,99,255,0.4);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 32px 0;
        }

        /* Invitations table */
        .invitations-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .inv-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .inv-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            transition: border-color 0.2s;
        }

        .inv-item:hover {
            border-color: rgba(108,99,255,0.2);
        }

        .inv-email {
            font-size: 14px;
            color: var(--text);
        }

        .inv-meta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .role-chip {
            font-size: 11px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 100px;
        }

        .role-chip.manager {
            background: rgba(108,99,255,0.15);
            color: #a89fff;
        }

        .role-chip.employee {
            background: rgba(0,217,126,0.15);
            color: var(--success);
        }

        .role-chip.client {
            background: rgba(255,214,102,0.15);
            color: var(--warning);
        }

        .inv-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        .empty-state {
            text-align: center;
            padding: 32px;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 32px;
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
            <div class="badge-label">Team</div>
            <h1>Invita un <span>nuovo membro</span></h1>
            <p>Aggiungi collaboratori al tuo team assegnando il ruolo più adatto.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">⚠ {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form invito --}}
        <div class="card">
            <div class="card-title">Nuovo invito</div>

            <form method="POST" action="{{ route('invitation.invite') }}">
                @csrf

                <div class="form-group">
                    <label>Email dell'utente</label>
                    <input type="email" name="email" placeholder="nome@esempio.com" required>
                </div>

                <div class="form-group">
                    <label>Ruolo</label>
                    <div class="role-grid">
                        <div class="role-option">
                            <input type="radio" name="role" id="role-manager" value="manager" checked>
                            <label class="role-label" for="role-manager">
                                <span class="role-icon">🎯</span>
                                <span class="role-name">Manager</span>
                                <span class="role-desc">Gestisce progetti</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" name="role" id="role-employee" value="employee">
                            <label class="role-label" for="role-employee">
                                <span class="role-icon">⚙️</span>
                                <span class="role-name">Employee</span>
                                <span class="role-desc">Lavora sui task</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" name="role" id="role-client" value="client">
                            <label class="role-label" for="role-client">
                                <span class="role-icon">👤</span>
                                <span class="role-name">Client</span>
                                <span class="role-desc">Visualizza progetti</span>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Invia invito →
                </button>
            </form>

            <div class="divider"></div>

           

        <a href="/admin" class="back-link">← Torna al pannello</a>

    </div>
</body>
</html>