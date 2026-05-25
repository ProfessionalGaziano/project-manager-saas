<?php

namespace App\Mail;

use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ProjectRequest $projectRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Aggiornamento sulla tua richiesta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-request-rejected',
            with: [
                'projectRequest'  => $this->projectRequest,
                'clientName'      => $this->projectRequest->client->name,
                'projectName'     => $this->projectRequest->title,
                'rejectionReason' => $this->projectRequest->rejection_reason,
            ]
        );
    }
}