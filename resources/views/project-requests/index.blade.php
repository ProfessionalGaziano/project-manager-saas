<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le mie Richieste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div class="toast show align-items-center text-bg-success border-0 shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Form nuova richiesta --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4">Fai una nuova richiesta</h2>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('project-requests.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Titolo</label>
                                <input type="text" class="form-control" value="Richiesta di {{ backpack_user()->name }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descrizione del progetto o problema</label>
                                <textarea name="description" class="form-control" rows="5" 
                                    placeholder="Descrivi nel dettaglio cosa vorresti che fosse realizzato o il problema che vuoi risolvere..." 
                                    required minlength="20"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Budget disponibile (€) <span class="text-muted small">— opzionale</span></label>
                                <input type="number" name="budget" class="form-control" 
                                    min="0" step="0.01"
                                    placeholder="Es. 1500.00">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Data entro cui vorresti la soluzione</label>
                                <input type="date" name="desired_deadline" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Invia Richiesta
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="text-center mt-4">
                    <a href="/admin" class="btn btn-link">← Torna al pannello</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>