<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Mail\TaskAssignedToEmployee;
use Illuminate\Support\Facades\Mail;

class SendTaskAssignedEmail
{
    public function __construct()
    {
        //
    }

    public function handle(TaskAssigned $event): void
    {
        Log::info('LISTENER ARRIVATO');
        dd('LISTENER OK');
        
        $task = $event->task;

        // carica dati necessari in modo sicuro
        $task->load(['assignedTo', 'project']);

        $user = $task->assignedTo;

        if (!$user) {
            return;
        }

        Mail::to($user->email)
            ->send(new TaskAssignedToEmployee(
                $user->name,
                $task->title,
                $task->project->name,
                $task->priority,
                $task->due_date?->format('d/m/Y')
            ));
    }
}