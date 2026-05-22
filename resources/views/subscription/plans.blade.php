<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piani di Abbonamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-5">Scegli il tuo Piano</h1>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center g-4">

            {{-- Piano Free --}}
            <div class="col-md-4">
                <div class="card h-100 shadow-sm {{ $onFreePlan ? 'border-primary border-2' : '' }}">
                    <div class="card-body text-center p-4">
                        <h2 class="card-title">Free</h2>
                        <h3 class="display-4 my-3">€0 <small class="fs-6 text-muted">/mese</small></h3>
                        <ul class="list-unstyled mb-4">
                            <li>✅ 1 Team</li>
                            <li>✅ 3 Progetti</li>
                            <li>✅ 10 Task</li>
                            <li>❌ Fatture</li>
                            <li>❌ Supporto prioritario</li>
                        </ul>
                        @if($onFreePlan)
                            <button class="btn btn-outline-primary w-100" disabled>Piano Attuale</button>
                        @else
                            <form method="POST" action="{{ route('subscription.cancel') }}">
                                @csrf
                                <button class="btn btn-outline-secondary w-100">Torna al Free</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Piano Pro --}}
            <div class="col-md-4">
                <div class="card h-100 shadow-sm {{ $onProPlan ? 'border-success border-2' : '' }}">
                    <div class="card-body text-center p-4">
                        <h2 class="card-title">Pro</h2>
                        <h3 class="display-4 my-3">€29 <small class="fs-6 text-muted">/mese</small></h3>
                        <ul class="list-unstyled mb-4">
                            <li>✅ Team illimitati</li>
                            <li>✅ Progetti illimitati</li>
                            <li>✅ Task illimitati</li>
                            <li>✅ Fatture</li>
                            <li>✅ Supporto prioritario</li>
                        </ul>
                        @if($onProPlan)
                            <button class="btn btn-success w-100" disabled>Piano Attuale</button>
                        @else
                            <form method="POST" action="{{ route('subscription.checkout') }}">
                                @csrf
                                <button class="btn btn-success w-100">Passa a Pro</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="/admin" class="btn btn-link">← Torna al pannello</a>
        </div>
    </div>
</body>
</html>