<?php

namespace App\Mail;

use App\Models\Reference;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferenceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $reference;

    /**
     * Create a new message instance.
     */
    public function __construct(public Reference $reference)
    {
        $reference->load('candidate.user');
        // $this->reference = $reference;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reference Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reference_request',
            with: [
                'candidate_name' => $this->reference->candidate->user->name ?? 'NA',
                'reference_name' => $this->reference->name ?? 'NA',
                'url' => route('feedback.edit', ['reference' => $this->reference->id]) . '?code=' . $this->reference->code,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
