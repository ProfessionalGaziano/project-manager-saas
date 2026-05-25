<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectAssignedToManager extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📋 Ti è stato assegnato un nuovo progetto!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-assigned-to-manager',
            with: [
                'project'     => $this->project,
                'managerName' => $this->project->manager->name,
                'projectName' => $this->project->name,
                'deadline'    => $this->project->deadline?->format('d/m/Y'),
            ]
        );
    }
}