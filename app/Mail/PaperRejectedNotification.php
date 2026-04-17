<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaperRejectedNotification extends Mailable
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
            subject: 'Editorial Decision: Manuscript Rejected - ' . $this->submission->journalTheme->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.academic-journal',
            with: [
                'decisionType' => 'REJECTED',
                'openingText' => 'Thank you for your submission. After careful evaluation by our editorial board and reviewers, we regret to inform you that your manuscript has been <strong>declined</strong> for publication.'
            ]
        );
    }
}