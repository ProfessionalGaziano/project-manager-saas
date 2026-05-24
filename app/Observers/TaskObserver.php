<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function updated(Task $task): void
    {
        // Controlla solo quando lo status cambia a 'done'
        if ($task->isDirty('status') && $task->status === 'done') {
            $project = $task->project;

            // Controlla se tutti i task del progetto sono 'done'
            $allDone = $project->tasks()
                ->where('status', '!=', 'done')
                ->doesntExist();

            if ($allDone && $project->tasks()->count() > 0) {
                $project->update(['status' => 'completed']);
            }
        }
    }
}