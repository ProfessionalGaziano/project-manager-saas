<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignedToEmployee;
use App\Mail\ProjectCompleted;
use App\Models\ProjectRequest;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    public function created(Task $task): void
    {
        // Invia email all'employee quando viene assegnato un task alla creazione
        if ($task->assigned_to !== null) {
            Mail::to($task->assignedTo->email)
                ->send(new TaskAssignedToEmployee($task));
        }
    }
    public function updated(Task $task): void
    {
       if ($task->wasChanged('assigned_to') && $task->assigned_to) {
        
        Log::info('Observer entrato', [
            'task_id' => $task->id,
            'employee' => $task->assignedTo?->email
        ]);

        try {
                Log::info('Prima della mail');

                Mail::to($task->assignedTo->email)
                    ->send(new TaskAssignedToEmployee($task));

                Log::info('Dopo la mail');

            } catch (\Throwable $e) {
                Log::error('ERRORE MAIL', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        if ($task->wasChanged('status') && $task->status === 'done') {

            $project = $task->project;

            $allDone = $project->tasks()
                ->where('status','!=','done')
                ->doesntExist();

            if ($allDone && $project->tasks()->count() > 0) {

                $project->update([
                    'status' => 'completed'
                ]);

                $projectRequest =
                    ProjectRequest::where(
                        'converted_to_project_id',
                        $project->id
                    )->first();

                if ($projectRequest) {

                    Mail::to(
                        $projectRequest->client->email
                    )->send(
                        new ProjectCompleted($project)
                    );
                }
            }
        }
    }
}