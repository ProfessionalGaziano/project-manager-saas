<?php

namespace App\Mail;

use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectRequestAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ProjectRequest $projectRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ La tua richiesta è stata accettata!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-request-accepted',
            with: [ 
                'projectRequest' => $this->projectRequest,
                'clientName'     => $this->projectRequest->client->name,
                'projectName'    => $this->projectRequest->title,
                'deadline'       => \Carbon\Carbon::parse($this->projectRequest->desired_deadline)->format('d/m/Y'),
            ]
        );
    }
}