<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // GET /api/projects
    public function index(Request $request)
    {
        $user = $request->user();
        $team = $user->ownedTeams()->first() ?? $user->teams()->first();

        if (!$team) {
            return response()->json(['message' => 'Nessun team trovato.'], 404);
        }

        // Filtra in base al ruolo
        if ($user->hasRole('manager')) {
            $projects = Project::where('manager_id', $user->id)
                ->with(['tasks', 'manager'])
                ->get();
        } elseif ($user->hasRole('client')) {
            $projectIds = \App\Models\ProjectRequest::where('client_id', $user->id)
                ->where('status', 'accepted')
                ->whereNotNull('converted_to_project_id')
                ->pluck('converted_to_project_id');
            $projects = Project::whereIn('id', $projectIds)
                ->with(['tasks'])
                ->get();
        } else {
            $projects = Project::where('team_id', $team->id)
                ->with(['tasks', 'manager'])
                ->get();
        }

        return response()->json([
            'data'  => $projects,
            'total' => $projects->count(),
        ]);
    }

    // GET /api/projects/{id}
    public function show(Request $request, Project $project)
    {
        $project->load(['tasks', 'manager', 'team']);

        return response()->json([
            'data' => $project,
        ]);
    }
}