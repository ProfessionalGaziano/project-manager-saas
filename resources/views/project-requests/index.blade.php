<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova Richiesta</title>
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
            max-width: 680px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        /* Toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: rgba(0,217,126,0.15);
            border: 1px solid rgba(0,217,126,0.3);
            color: var(--success);
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--success);
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 0;
            margin-left: 8px;
        }

        /* Header */
        .header {
            margin-bottom: 40px;
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
            font-size: clamp(28px, 4vw, 40px);
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

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            animation: fadeUp 0.7s ease 0.1s both;
        }

        /* Alert */
        .alert-danger {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.3);
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .alert-danger ul { padding-left: 16px; margin: 0; }

        /* Form */
        .form-group {
            margin-bottom: 22px;
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

        .label-optional {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: none;
            letter-spacing: 0;
            opacity: 0.7;
            margin-left: 6px;
        }

        input, textarea {
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
            resize: none;
        }

        input:focus, textarea:focus {
            border-color: rgba(108,99,255,0.5);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.1);
        }

        input:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        input::placeholder, textarea::placeholder {
            color: var(--text-muted);
        }

        /* Two columns */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 540px) {
            .form-row { grid-template-columns: 1fr; }
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 28px 0;
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
            transition: all 0.2s ease;
            box-shadow: 0 8px 24px rgba(108,99,255,0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(108,99,255,0.4);
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

        /* Info box */
        .info-box {
            background: rgba(108,99,255,0.08);
            border: 1px solid rgba(108,99,255,0.2);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 13px;
            color: #a89fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>

    {{-- Toast --}}
    @if(session('success'))
    <div class="toast" id="toast">
        ✓ {{ session('success') }}
        <button class="toast-close" onclick="document.getElementById('toast').remove()">×</button>
    </div>
    @endif

    <div class="wrapper">

        <div class="header">
            <div class="badge-label">Nuova Richiesta</div>
            <h1>Cosa possiamo<br><span>fare per te?</span></h1>
            <p>Descrivi il tuo progetto o problema — il nostro team ti risponderà al più presto.</p>
        </div>

        <div class="card">

            @if($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="info-box">
                💡 La richiesta verrà inviata al nostro team che la valuterà e ti risponderà via email.
            </div>

            <form method="POST" action="{{ route('project-requests.store') }}">
                @csrf

                <div class="form-group">
                    <label>Richiedente</label>
                    <input type="text" value="{{ backpack_user()->name }}" disabled>
                </div>

                <div class="divider"></div>

                <div class="form-group">
                    <label>Descrizione del progetto o problema</label>
                    <textarea
                        name="description"
                        rows="6"
                        placeholder="Descrivi nel dettaglio cosa vorresti che fosse realizzato o il problema che vuoi risolvere..."
                        required
                        minlength="20">{{ old('description') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Budget disponibile <span class="label-optional">— opzionale</span></label>
                        <input
                            type="number"
                            name="budget"
                            min="0"
                            step="0.01"
                            placeholder="Es. 1500.00"
                            value="{{ old('budget') }}">
                    </div>

                    <div class="form-group">
                        <label>Scadenza desiderata</label>
                        <input
                            type="date"
                            name="desired_deadline"
                            required
                            value="{{ old('desired_deadline') }}">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Invia richiesta →
                </button>
            </form>
        </div>

        <a href="/admin/dashboard" class="back-link">← Torna alla dashboard</a>

    </div>

</body>
</html>