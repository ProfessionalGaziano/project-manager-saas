<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accetta Invito</title>
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
                radial-gradient(ellipse at 30% 30%, rgba(108,99,255,0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 70%, rgba(0,217,126,0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 24px;
            animation: fadeUp 0.6s ease both;
        }

        /* Team info header */
        .team-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .team-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #6c63ff, #9b6fff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 16px;
            box-shadow: 0 8px 32px rgba(108,99,255,0.3);
        }

        .team-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
        }

        .invite-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 8px 18px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .role-chip {
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 100px;
            background: rgba(108,99,255,0.15);
            color: #a89fff;
        }

        .role-chip.manager { background: rgba(108,99,255,0.15); color: #a89fff; }
        .role-chip.employee { background: rgba(0,217,126,0.15); color: var(--success); }
        .role-chip.client { background: rgba(255,214,102,0.15); color: #ffd666; }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 36px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .alert-danger {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.3);
            color: var(--danger);
        }

        .alert ul { padding-left: 16px; margin: 0; }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: rgba(108,99,255,0.5);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
        }

        input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        input::placeholder { color: var(--text-muted); }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 24px 0;
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

        /* Footer note */
        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Team header --}}
        <div class="team-header">
            <div class="team-avatar">🚀</div>
            <h1>Sei stato invitato!</h1>
            <div class="invite-info">
                Unisciti a <strong style="color: var(--text); margin: 0 4px;">{{ $invitation->team->name }}</strong>
                come
                <span class="role-chip {{ $invitation->role }}">{{ $invitation->role }}</span>
            </div>
        </div>

        {{-- Form card --}}
        <div class="card">

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

            <form method="POST" action="{{ route('invitation.register', $token) }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="{{ $invitation->email }}" disabled>
                </div>

                <div class="divider"></div>

                <div class="form-group">
                    <label>Il tuo nome</label>
                    <input type="text" name="name" placeholder="Mario Rossi" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min. 8 caratteri" required minlength="8">
                </div>

                <div class="form-group">
                    <label>Conferma Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ripeti la password" required>
                </div>

                <button type="submit" class="btn-submit">
                    Entra nel team →
                </button>
            </form>
        </div>

        <div class="footer-note">
            Riceverai una email di verifica dopo la registrazione.<br>
            L'invito scade il {{ $invitation->expires_at->format('d/m/Y') }}.
        </div>

    </div>
</body>
</html>