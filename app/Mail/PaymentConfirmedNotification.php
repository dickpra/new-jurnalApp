<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmedNotification extends Mailable
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
            subject: 'Payment Confirmed: Your Manuscript is Ready for Publication',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.academic-journal',
            with: [
                'decisionType' => 'PAID',
                'openingText' => 'We are pleased to inform you that we have <strong>verified your payment</strong>. Your manuscript has now been officially cleared for the next stage of publication.'
            ]
        );
    }
}