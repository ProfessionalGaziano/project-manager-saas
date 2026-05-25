<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskAssignedToEmployee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Task $task
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Ti è stato assegnato un nuovo task!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-assigned-to-employee',
            with: [
                'task'         => $this->task,
                'employeeName' => $this->task->assignedTo->name,
                'taskTitle'    => $this->task->title,
                'projectName'  => $this->task->project->name,
                'priority'     => $this->task->priority,
                'dueDate'      => \Carbon\Carbon::parse($this->task->due_date)->format('d/m/Y'),
            ]
        );
    }
}