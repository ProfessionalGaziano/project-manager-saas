<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Il tuo progetto è stato completato!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-completed',
            with: [
                'project'     => $this->project,
                'projectName' => $this->project->name,
            ]
        );
    }
}