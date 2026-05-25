<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Mail\ProjectRequestAccepted;
use App\Mail\ProjectRequestRejected;
use Illuminate\Support\Facades\Mail;

class ProjectRequestController extends Controller
{
    // Client vede le sue richieste e può crearne una nuova
    public function index()
    {
        $requests = ProjectRequest::where('client_id', backpack_user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('project-requests.index', compact('requests'));
    }

    // Client crea una nuova richiesta
    public function store(Request $request)
    {
        $request->validate([
            'description'      => 'required|string|min:20',
            'desired_deadline' => 'required|date|after:today',
            'budget'           => 'nullable|numeric|min:0',
        ]);

        ProjectRequest::create([
            'client_id'        => backpack_user()->id,
            'title'            => 'Richiesta di ' . backpack_user()->name,
            'description'      => $request->description,
            'budget'           => $request->budget,
            'desired_deadline' => $request->desired_deadline,
            'status'           => 'pending',
        ]);
        
     return redirect()->to('/admin/dashboard')->with('success', 'Richiesta inviata con successo! Un admin la prenderà in carico a breve.');
    
    }

    // Admin vede tutte le richieste pending
    public function adminIndex()
    {
        $requests = ProjectRequest::with(['client'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('project-requests.admin-index', compact('requests'));
    }

    // Admin accetta la richiesta e la blocca
    public function accept(ProjectRequest $projectRequest)
    {
        if (!$projectRequest->isPending()) {
            return back()->with('error', 'Questa richiesta è già stata presa in carico.');
        }

        // Crea automaticamente il progetto in bozza
        $project = Project::create([
            'team_id'     => backpack_user()->ownedTeams()->first()->id,
            'name'        => $projectRequest->title,
            'description' => $projectRequest->description,
            'status'      => 'draft',
            'deadline'    => $projectRequest->desired_deadline,
        ]);

        // Aggiorna la richiesta
        $projectRequest->update([
            'status'                  => 'accepted',
            'assigned_admin_id'       => backpack_user()->id,
            'converted_to_project_id' => $project->id,
        ]);

        
        Mail::to($projectRequest->client->email)->send(new ProjectRequestAccepted($projectRequest));
       

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Richiesta accettata! Il progetto è stato creato in bozza.');
    }

    // Admin rifiuta la richiesta con motivazione
    public function reject(Request $request, ProjectRequest $projectRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if (!$projectRequest->isPending()) {
            return back()->with('error', 'Questa richiesta è già stata presa in carico.');
        }

        $projectRequest->update([
            'status'           => 'rejected',
            'assigned_admin_id' => backpack_user()->id,
            'rejection_reason' => $request->rejection_reason,
        ]);

        // TODO: Invia email al client con la motivazione del rifiuto
        // Invia email al client con motivazione
        Mail::to($projectRequest->client->email)->send(new ProjectRequestRejected($projectRequest));

        return redirect()->to('/admin/dashboard')
              ->with('success', 'Richiesta rifiutata.');
    }
}