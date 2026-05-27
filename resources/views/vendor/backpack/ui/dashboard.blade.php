@extends(backpack_view('blank'))

@php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    }
@endphp

@section('content')

@php
    $user = backpack_user();
    $team = $user->ownedTeams()->first();

    // Dati Admin
    if ($user->hasRole('admin') && $team) {
        $projectsByStatus = \App\Models\Project::where('team_id', $team->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalRevenue = \App\Models\Invoice::where('team_id', $team->id)
            ->where('status', 'paid')->sum('amount');

        $pendingRevenue = \App\Models\Invoice::where('team_id', $team->id)
            ->where('status', 'sent')->sum('amount');

        $requestsThisMonth = \App\Models\ProjectRequest::whereMonth('created_at', now()->month)->count();

        $pendingRequests = \App\Models\ProjectRequest::with('client')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $membersByRole = $team->users()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, count(*) as count')
            ->groupBy('roles.name')
            ->pluck('count', 'role')
            ->toArray();
    }

    // Dati Manager
    if ($user->hasRole('manager')) {
        $myProjects = \App\Models\Project::where('manager_id', $user->id)->get();

        $myTasksByStatus = \App\Models\Task::whereHas('project', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->selectRaw('status, count(*) as count')
          ->groupBy('status')
          ->pluck('count', 'status')
          ->toArray();

        $urgentTasks = \App\Models\Task::whereHas('project', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->where('status', '!=', 'done')
          ->whereNotNull('due_date')
          ->orderBy('due_date')
          ->take(5)->get();
    }

    // Dati Employee
    if ($user->hasRole('employee')) {
        $myTasks = \App\Models\Task::where('assigned_to', $user->id)->get();

        $completedThisMonth = \App\Models\Task::where('assigned_to', $user->id)
            ->where('status', 'done')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $urgentTasks = \App\Models\Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->take(5)->get();
    }
@endphp

<style>
    :root {
        --accent: #6c63ff;
        --success: #00d97e;
        --warning: #ffd666;
        --danger: #ff6b6b;
        --surface: #13131a;
        --surface-2: #1c1c26;
        --border: rgba(255,255,255,0.06);
        --text: #f0f0f5;
        --text-muted: #7070a0;
    }

    .dash-wrapper {
        padding: 24px;
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
    }

    /* Welcome bar */
    .welcome-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-bar h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .welcome-bar p {
        color: var(--text-muted);
        font-size: 14px;
        margin: 0;
    }

    .plan-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 600;
    }

    .plan-pill.pro {
        background: rgba(0,217,126,0.15);
        color: var(--success);
        border: 1px solid rgba(0,217,126,0.3);
    }

    .plan-pill.free {
        background: rgba(255,214,102,0.15);
        color: var(--warning);
        border: 1px solid rgba(255,214,102,0.3);
    }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        transition: border-color 0.2s, transform 0.2s;
    }

    .stat-card:hover {
        border-color: rgba(108,99,255,0.3);
        transform: translateY(-2px);
    }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Charts grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    .chart-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px;
    }

    .chart-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
    }

    canvas {
        max-height: 220px;
    }

    /* Requests */
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .badge-count {
        background: rgba(255,107,107,0.2);
        color: var(--danger);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 100px;
    }

    /* Request item */
    .req-item {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 12px;
        transition: border-color 0.2s;
    }

    .req-item:hover { border-color: rgba(108,99,255,0.2); }

    .req-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .req-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .req-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 100px;
        background: rgba(255,214,102,0.15);
        color: var(--warning);
    }

    .req-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 14px;
    }

    .req-actions {
        display: flex;
        gap: 10px;
    }

    .btn-accept {
        padding: 8px 18px;
        background: rgba(0,217,126,0.15);
        color: var(--success);
        border: 1px solid rgba(0,217,126,0.3);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-accept:hover {
        background: rgba(0,217,126,0.25);
    }

    .btn-reject-toggle {
        padding: 8px 18px;
        background: rgba(255,107,107,0.1);
        color: var(--danger);
        border: 1px solid rgba(255,107,107,0.2);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reject-toggle:hover {
        background: rgba(255,107,107,0.2);
    }

    .reject-form {
        margin-top: 14px;
        display: none;
    }

    .reject-form textarea {
        width: 100%;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px;
        color: var(--text);
        font-size: 13px;
        resize: none;
        outline: none;
        margin-bottom: 10px;
    }

    .btn-reject-confirm {
        width: 100%;
        padding: 10px;
        background: rgba(255,107,107,0.15);
        color: var(--danger);
        border: 1px solid rgba(255,107,107,0.3);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Progress bars */
    .progress-item {
        margin-bottom: 16px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .progress-name {
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
    }

    .progress-pct {
        font-size: 12px;
        color: var(--text-muted);
    }

    .progress-bar-bg {
        height: 6px;
        background: var(--surface-2);
        border-radius: 100px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 100px;
        background: linear-gradient(90deg, #6c63ff, #9b6fff);
        transition: width 1s ease;
    }

    /* Task list */
    .task-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }

    .task-item:last-child { border-bottom: none; }

    .task-priority {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .task-priority.high { background: var(--danger); }
    .task-priority.medium { background: var(--warning); }
    .task-priority.low { background: var(--success); }

    .task-info { flex: 1; }

    .task-title {
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
        margin-bottom: 3px;
    }

    .task-project {
        font-size: 11px;
        color: var(--text-muted);
    }

    .task-due {
        font-size: 11px;
        color: var(--text-muted);
        white-space: nowrap;
    }

    .task-status {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 100px;
    }

    .task-status.todo { background: rgba(112,112,160,0.15); color: var(--text-muted); }
    .task-status.in_progress { background: rgba(108,99,255,0.15); color: #a89fff; }
    .task-status.review { background: rgba(255,214,102,0.15); color: var(--warning); }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
        font-size: 14px;
    }

    /* Alert toast */
    .toast-success {
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
        font-size: 18px;
        line-height: 1;
        padding: 0;
        margin-left: 8px;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dash-wrapper">

    {{-- Toast --}}
    @if(session('success'))
    <div class="toast-success" id="toast">
        ✓ {{ session('success') }}
        <button class="toast-close" onclick="document.getElementById('toast').remove()">×</button>
    </div>
    @endif

    {{-- Welcome bar --}}
    <div class="welcome-bar">
        <div>
            <h2>👋 Bentornato, {{ $user->name }}!</h2>
            <p>{{ now()->format('l, d F Y') }}</p>
        </div>
        @if($user->hasRole('admin') && $team)
            <div class="plan-pill {{ $team->plan === 'pro' ? 'pro' : 'free' }}">
                {{ $team->plan === 'pro' ? '⭐ Piano Pro' : '🆓 Piano Free' }}
            </div>
        @endif
    </div>

    {{-- ===== ADMIN DASHBOARD ===== --}}
    @if($user->hasRole('admin') && $team)

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📁</div>
                <div class="stat-value">{{ array_sum($projectsByStatus) }}</div>
                <div class="stat-label">Progetti totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💶</div>
                <div class="stat-value">€{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-label">Fatturato incassato</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value">€{{ number_format($pendingRevenue, 0, ',', '.') }}</div>
                <div class="stat-label">In attesa di pagamento</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📨</div>
                <div class="stat-value">{{ $requestsThisMonth }}</div>
                <div class="stat-label">Richieste questo mese</div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">📊 Progetti per stato</div>
                <canvas id="projectsChart"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title">👥 Membri per ruolo</div>
                <canvas id="membersChart"></canvas>
            </div>
        </div>

        {{-- Pending requests --}}
        @if($pendingRequests->count() > 0)
        <div class="section-card">
            <div class="section-title">
                Richieste in arrivo
                <span class="badge-count">{{ $pendingRequests->count() }}</span>
            </div>
            @foreach($pendingRequests as $request)
            <div class="req-item">
                <div class="req-header">
                    <div class="req-title">{{ $request->title }}</div>
                    <span class="req-badge">⏳ In attesa</span>
                </div>
                <div class="req-meta">
                    {{ $request->clientLoyaltyBadge() }} · Scadenza: {{ $request->desired_deadline->format('d/m/Y') }}
                    @if($request->budget) · Budget: <strong>€{{ number_format($request->budget, 2) }}</strong> @endif
                </div>
                <div class="req-actions">
                    <form method="POST" action="{{ route('project-requests.accept', $request->id) }}">
                        @csrf
                        <button type="submit" class="btn-accept">✅ Accetta</button>
                    </form>
                    <button class="btn-reject-toggle" onclick="toggleReject({{ $request->id }})">❌ Rifiuta</button>
                </div>
                <div class="reject-form" id="reject-{{ $request->id }}">
                    <form method="POST" action="{{ route('project-requests.reject', $request->id) }}">
                        @csrf
                        <textarea name="rejection_reason" rows="2" required minlength="10" placeholder="Motivazione del rifiuto..."></textarea>
                        <button type="submit" class="btn-reject-confirm">Conferma rifiuto</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    {{-- ===== MANAGER DASHBOARD ===== --}}
    @elseif($user->hasRole('manager'))

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📁</div>
                <div class="stat-value">{{ $myProjects->count() }}</div>
                <div class="stat-label">I miei progetti</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">{{ $myTasksByStatus['done'] ?? 0 }}</div>
                <div class="stat-label">Task completati</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-value">{{ $myTasksByStatus['in_progress'] ?? 0 }}</div>
                <div class="stat-label">Task in corso</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">{{ $myTasksByStatus['todo'] ?? 0 }}</div>
                <div class="stat-label">Task da fare</div>
            </div>
        </div>

        <div class="charts-grid">
            {{-- Task per stato --}}
            <div class="chart-card">
                <div class="chart-title">📊 Task per stato</div>
                <canvas id="tasksChart"></canvas>
            </div>

            {{-- Avanzamento progetti --}}
            <div class="chart-card">
                <div class="chart-title">📈 Avanzamento progetti</div>
                @forelse($myProjects as $project)
                    @php
                        $total = $project->tasks()->count();
                        $done = $project->tasks()->where('status', 'done')->count();
                        $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                    @endphp
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-name">{{ $project->name }}</span>
                            <span class="progress-pct">{{ $pct }}%</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Nessun progetto assegnato.</div>
                @endforelse
            </div>
        </div>

        {{-- Task urgenti --}}
        @if($urgentTasks->count() > 0)
        <div class="section-card">
            <div class="section-title">⚠️ Scadenze imminenti</div>
            @foreach($urgentTasks as $task)
            <div class="task-item">
                <div class="task-priority {{ $task->priority }}"></div>
                <div class="task-info">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-project">{{ $task->project->name }}</div>
                </div>
                <span class="task-status {{ $task->status }}">{{ str_replace('_', ' ', $task->status) }}</span>
                <span class="task-due">{{ $task->due_date->format('d/m') }}</span>
            </div>
            @endforeach
        </div>
        @endif

    {{-- ===== EMPLOYEE DASHBOARD ===== --}}
    @elseif($user->hasRole('employee'))

        @php
            $totalTasks = $myTasks->count();
            $doneTasks = $myTasks->where('status', 'done')->count();
            $progressPct = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
        @endphp

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">{{ $totalTasks }}</div>
                <div class="stat-label">Task totali assegnati</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">{{ $completedThisMonth }}</div>
                <div class="stat-label">Completati questo mese</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-value">{{ $myTasks->where('status', 'in_progress')->count() }}</div>
                <div class="stat-label">In corso</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-value">{{ $progressPct }}%</div>
                <div class="stat-label">Completamento totale</div>
            </div>
        </div>

        <div class="charts-grid">
            {{-- Progresso personale --}}
            <div class="chart-card">
                <div class="chart-title">🎯 Il mio progresso</div>
                <canvas id="employeeChart"></canvas>
            </div>

            {{-- Task urgenti --}}
            <div class="chart-card">
                <div class="chart-title">⚠️ Scadenze imminenti</div>
                @forelse($urgentTasks as $task)
                <div class="task-item">
                    <div class="task-priority {{ $task->priority }}"></div>
                    <div class="task-info">
                        <div class="task-title">{{ $task->title }}</div>
                        <div class="task-project">{{ $task->project->name }}</div>
                    </div>
                    <span class="task-status {{ $task->status }}">{{ str_replace('_', ' ', $task->status) }}</span>
                    <span class="task-due">{{ $task->due_date->format('d/m') }}</span>
                </div>
                @empty
                    <div class="empty-state">Nessuna scadenza imminente 🎉</div>
                @endforelse
            </div>
        </div>

    {{-- ===== CLIENT DASHBOARD ===== --}}
    @elseif($user->hasRole('client'))

        <div class="section-card">
            <div class="section-title">📋 Le tue richieste</div>
            @php
                $requests = \App\Models\ProjectRequest::where('client_id', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            @endphp
            @forelse($requests as $request)
                <div class="req-item">
                    <div class="req-header">
                        <div class="req-title">{{ $request->title }}</div>
                        @if($request->status === 'pending')
                            <span class="req-badge">⏳ In attesa</span>
                        @elseif($request->status === 'accepted')
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 10px;border-radius:100px;background:rgba(0,217,126,0.15);color:#00d97e;">✅ Accettata</span>
                        @else
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 10px;border-radius:100px;background:rgba(255,107,107,0.15);color:#ff6b6b;">❌ Rifiutata</span>
                        @endif
                    </div>
                    <div class="req-meta">
                        Scadenza: {{ $request->desired_deadline->format('d/m/Y') }}
                        @if($request->budget) · Budget: €{{ number_format($request->budget, 2) }} @endif
                    </div>
                    @if($request->status === 'rejected' && $request->rejection_reason)
                        <div style="background:rgba(255,107,107,0.1);border:1px solid rgba(255,107,107,0.2);border-radius:8px;padding:10px;margin-top:10px;font-size:12px;color:#ff6b6b;">
                            Motivazione: {{ $request->rejection_reason }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">Non hai ancora fatto nessuna richiesta.</div>
            @endforelse
            <div style="margin-top:20px;">
                <a href="{{ route('project-requests.index') }}" style="display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#6c63ff,#9b6fff);color:white;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;">
                    + Nuova richiesta
                </a>
                <a href="{{ backpack_url('logout') }}" style="display:inline-block;padding:12px 24px;background:rgba(255,107,107,0.1);color:#ff6b6b;border:1px solid rgba(255,107,107,0.2);border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;margin-left:10px;">
                    Logout
                </a>
            </div>
        </div>

    @endif

</div>

<script>
    // Toggle reject form
    function toggleReject(id) {
        const el = document.getElementById('reject-' + id);
        el.style.display = el.style.display === 'block' ? 'none' : 'block';
    }

    // Chart defaults
    Chart.defaults.color = '#7070a0';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';

    @if($user->hasRole('admin') && isset($team))
    // Projects chart
    const projectsCtx = document.getElementById('projectsChart');
    if (projectsCtx) {
        new Chart(projectsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bozza', 'Attivo', 'Completato', 'Archiviato'],
                datasets: [{
                    data: [
                        {{ $projectsByStatus['draft'] ?? 0 }},
                        {{ $projectsByStatus['active'] ?? 0 }},
                        {{ $projectsByStatus['completed'] ?? 0 }},
                        {{ $projectsByStatus['archived'] ?? 0 }}
                    ],
                    backgroundColor: ['#7070a0', '#6c63ff', '#00d97e', '#ffd666'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } } },
                cutout: '70%'
            }
        });
    }

    // Members chart
    const membersCtx = document.getElementById('membersChart');
    if (membersCtx) {
        new Chart(membersCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys({!! json_encode($membersByRole) !!}),
                datasets: [{
                    data: Object.values({!! json_encode($membersByRole) !!}),
                    backgroundColor: ['#6c63ff', '#00d97e', '#ffd666', '#ff6b6b'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } } },
                cutout: '70%'
            }
        });
    }
    @endif

    @if($user->hasRole('manager'))
    // Tasks chart
    const tasksCtx = document.getElementById('tasksChart');
    if (tasksCtx) {
        new Chart(tasksCtx, {
            type: 'doughnut',
            data: {
                labels: ['Da fare', 'In corso', 'Review', 'Completato'],
                datasets: [{
                    data: [
                        {{ $myTasksByStatus['todo'] ?? 0 }},
                        {{ $myTasksByStatus['in_progress'] ?? 0 }},
                        {{ $myTasksByStatus['review'] ?? 0 }},
                        {{ $myTasksByStatus['done'] ?? 0 }}
                    ],
                    backgroundColor: ['#7070a0', '#6c63ff', '#ffd666', '#00d97e'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } } },
                cutout: '70%'
            }
        });
    }
    @endif

    @if($user->hasRole('employee'))
    // Employee progress chart
    const employeeCtx = document.getElementById('employeeChart');
    if (employeeCtx) {
        new Chart(employeeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Da fare', 'In corso', 'Review', 'Completati'],
                datasets: [{
                    data: [
                        {{ $myTasks->where('status', 'todo')->count() }},
                        {{ $myTasks->where('status', 'in_progress')->count() }},
                        {{ $myTasks->where('status', 'review')->count() }},
                        {{ $myTasks->where('status', 'done')->count() }}
                    ],
                    backgroundColor: ['#7070a0', '#6c63ff', '#ffd666', '#00d97e'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } } },
                cutout: '70%'
            }
        });
    }
    @endif
</script>

@endsection