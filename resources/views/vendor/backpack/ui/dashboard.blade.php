@extends(backpack_view('blank'))


@php
    // Non mostrare nulla prima del contenuto
@endphp

@section('content')

    {{-- Toast notifica --}}
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

    {{-- Box richieste per il client --}}
    @if(backpack_user()->hasRole('client'))
        <div class="container-fluid">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Le tue richieste</h4>

                    @php
                        $requests = \App\Models\ProjectRequest::where('client_id', backpack_user()->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    @if($requests->count() > 0)
                        @foreach($requests as $request)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $request->title }}</h6>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning">⏳ In attesa</span>
                                    @elseif($request->status === 'accepted')
                                        <span class="badge bg-success">✅ Accettata</span>
                                    @else
                                        <span class="badge bg-danger">❌ Rifiutata</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-1">{{ $request->description }}</p>
                                <p class="text-muted small mb-0">
                                    Scadenza desiderata: <strong>{{ $request->desired_deadline->format('d/m/Y') }}</strong>
                                    @if($request->budget)
                                        &nbsp;|&nbsp; Budget: <strong class="text-success">€ {{ number_format($request->budget, 2) }}</strong>
                                    @endif
                                </p>
                                @if($request->status === 'rejected' && $request->rejection_reason)
                                    <div class="alert alert-danger mt-2 mb-0 py-2">
                                        <small>Motivazione rifiuto: {{ $request->rejection_reason }}</small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Non hai ancora fatto nessuna richiesta.</p>
                    @endif

                  <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('project-requests.index') }}" class="btn btn-primary">
                             Fai una richiesta
                        </a>

                        <a href="{{ backpack_url('logout') }}" class="btn btn-primary">
                            Logout
                        </a>
                  </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Box richieste in arrivo per l'admin --}}
   {{-- Box richieste in arrivo per l'admin --}}
@if(backpack_user()->hasRole('admin'))
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="mb-4">Richieste in arrivo</h4>

                @php
                    $pendingRequests = \App\Models\ProjectRequest::with('client')
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp

                @if($pendingRequests->count() > 0)
                    @foreach($pendingRequests as $request)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $request->title }}</h6>
                                    <small class="text-muted">
                                        {{ $request->clientLoyaltyBadge() }} 
                                        — {{ $request->clientRequestsCount() }} richieste totali
                                    </small>
                                </div>
                                <span class="badge bg-warning">⏳ In attesa</span>
                            </div>
                            <p class="text-muted small mb-2">{{ $request->description }}</p>
                            <p class="text-muted small mb-3">
                                Scadenza desiderata: <strong>{{ $request->desired_deadline->format('d/m/Y') }}</strong>
                                @if($request->budget)
                                    &nbsp;|&nbsp; Budget: <strong class="text-success">€ {{ number_format($request->budget, 2) }}</strong>
                                @endif
                            </p>

                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('project-requests.accept', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">✅ Accetta</button>
                                </form>

                                <button class="btn btn-danger btn-sm" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#reject-{{ $request->id }}">
                                    ❌ Rifiuta
                                </button>
                            </div>

                            <div class="collapse mt-3" id="reject-{{ $request->id }}">
                                <form method="POST" action="{{ route('project-requests.reject', $request->id) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="rejection_reason" class="form-control form-control-sm" 
                                            rows="2" required minlength="10"
                                            placeholder="Motivazione del rifiuto..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        Conferma rifiuto
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                        <p class="text-muted">Nessuna richiesta in attesa al momento. 🎉</p>
                    @endif
                        </div>
                    </div>
                </div>
            @endif

@endsection