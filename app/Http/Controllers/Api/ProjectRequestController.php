<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    // GET /api/project-requests
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            $requests = ProjectRequest::with('client')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $requests = ProjectRequest::where('client_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'data'  => $requests,
            'total' => $requests->count(),
        ]);
    }

    // POST /api/project-requests
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('client')) {
            return response()->json(['message' => 'Solo i client possono fare richieste.'], 403);
        }

        $request->validate([
            'description'      => 'required|string|min:20',
            'desired_deadline' => 'required|date|after:today',
            'budget'           => 'nullable|numeric|min:0',
        ]);

        $projectRequest = ProjectRequest::create([
            'client_id'        => $user->id,
            'title'            => 'Richiesta di ' . $user->name,
            'description'      => $request->description,
            'desired_deadline' => $request->desired_deadline,
            'budget'           => $request->budget,
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Richiesta inviata con successo.',
            'data'    => $projectRequest,
        ], 201);
    }
}