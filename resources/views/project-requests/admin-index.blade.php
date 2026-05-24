<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richieste di Progetto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Richieste di Progetto in arrivo</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($requests->count() > 0)
            @foreach($requests as $request)
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        
                        {{-- Header con titolo e badge fedeltà --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $request->title }}</h5>
                                <span class="text-muted small">
                                    {{ $request->clientLoyaltyBadge() }} 
                                    — {{ $request->clientRequestsCount() }} richieste totali
                                </span>
                            </div>
                            <span class="badge bg-warning">In attesa</span>
                        </div>

                        {{-- Dettagli richiesta --}}
                        <p class="mb-2">{{ $request->description }}</p>
                        <p class="text-muted small mb-3">
                            Scadenza desiderata: <strong>{{ $request->desired_deadline->format('d/m/Y') }}</strong>
                            &nbsp;|&nbsp;
                            Ricevuta il: <strong>{{ $request->created_at->format('d/m/Y') }}</strong>
                            @if($request->budget)
                                &nbsp;|&nbsp;
                                Budget: <strong class="text-success">€ {{ number_format($request->budget, 2) }}</strong>
                            @endif
                        </p>

                        {{-- Azioni --}}
                        <div class="d-flex gap-2">
                            {{-- Accetta --}}
                            <form method="POST" action="{{ route('project-requests.accept', $request->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    ✅ Accetta richiesta
                                </button>
                            </form>

                            {{-- Rifiuta --}}
                            <button class="btn btn-danger" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#reject-{{ $request->id }}">
                                ❌ Rifiuta richiesta
                            </button>
                        </div>

                        {{-- Form rifiuto collassabile --}}
                        <div class="collapse mt-3" id="reject-{{ $request->id }}">
                            <form method="POST" action="{{ route('project-requests.reject', $request->id) }}">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Motivazione del rifiuto</label>
                                    <textarea name="rejection_reason" class="form-control" rows="3" 
                                        required minlength="10"
                                        placeholder="Spiega al cliente perché la richiesta non può essere accettata..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    Conferma rifiuto
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        @else
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">
                    <p class="text-muted">Nessuna richiesta in attesa al momento.</p>
                </div>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="/admin" class="btn btn-link">← Torna al pannello</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>