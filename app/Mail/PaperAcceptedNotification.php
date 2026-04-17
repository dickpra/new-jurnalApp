<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaperAcceptedNotification extends Mailable
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
            subject: 'Editorial Decision: Manuscript Accepted - ' . $this->submission->journalTheme->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.academic-journal',
            with: [
                'decisionType' => 'ACCEPTED',
                'openingText' => 'We are pleased to inform you that your manuscript has successfully passed the peer-review process and has been <strong>accepted</strong> for publication.'
            ]
        );
    }
}