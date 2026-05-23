<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invita Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Invita un utente al team</h2>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
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

                        <form method="POST" action="{{ route('invitation.invite') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email dell'utente da invitare</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Ruolo</label>
                                <select name="role" class="form-select">
                                    <option value="manager">Manager</option>
                                    <option value="employee">Employee</option>
                                    <option value="client">Client</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Invia Invito
                            </button>
                        </form>

                        {{-- Lista inviti pendenti --}}
                        @if($invitations->count() > 0)
                            <hr class="my-4">
                            <h5>Inviti pendenti</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Ruolo</th>
                                        <th>Scadenza</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>{{ $invitation->email }}</td>
                                            <td>{{ $invitation->role }}</td>
                                            <td>{{ $invitation->expires_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <hr class="my-4">
                            <p class="text-muted text-center">Nessun invito pendente.</p>
                        @endif

                        <div class="text-center mt-3">
                            <a href="/admin" class="btn btn-link">← Torna al pannello</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>