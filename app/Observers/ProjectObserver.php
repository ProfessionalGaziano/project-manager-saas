<?php

namespace App\Observers;

use App\Models\Project;
use App\Mail\ProjectAssignedToManager;
use Illuminate\Support\Facades\Mail;

class ProjectObserver
{
    public function updated(Project $project): void
    {
        // Invia email al manager quando viene assegnato
        if ($project->isDirty('manager_id') && $project->manager_id !== null) {
            Mail::to($project->manager->email)
                ->send(new ProjectAssignedToManager($project));
        }
    }
}