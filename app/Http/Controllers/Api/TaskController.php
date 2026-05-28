<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // GET /api/projects/{project}/tasks
    public function index(Request $request, $projectId)
    {
        $user = $request->user();

        $tasks = Task::where('project_id', $projectId)
            ->when($user->hasRole('employee'), function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })
            ->with(['assignedTo', 'project'])
            ->get();

        return response()->json([
            'data'  => $tasks,
            'total' => $tasks->count(),
        ]);
    }

    // PATCH /api/tasks/{id}
    public function update(Request $request, Task $task)
    {
        $user = $request->user();

        // Employee può aggiornare solo i suoi task
        if ($user->hasRole('employee') && $task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Non autorizzato.'], 403);
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Task aggiornato con successo.',
            'data'    => $task->fresh(),
        ]);
    }
}