<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaperRevisionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Submission $submission;
    public string $customMessage;

    public function __construct(Submission $submission, string $customMessage)
    {
        $this->submission = $submission;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Editorial Decision: Revision Required - ' . $this->submission->journalTheme->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.academic-journal',
            with: [
                'decisionType' => 'REVISION',
                'openingText' => 'Your manuscript has completed the peer-review process. The editorial board has determined that your submission requires <strong>revisions</strong> before it can be reconsidered for publication.'
            ]
        );
    }
}