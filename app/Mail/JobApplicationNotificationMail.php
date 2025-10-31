<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public JobApplication $jobApplication
    ) {
        // Load necessary relationships
        $this->jobApplication->load(['candidate.user', 'job.employer']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application - ' . $this->jobApplication->job->title . ' - ' . config('app.name'),
            to: 'cv@hireplans.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application-notification',
            with: [
                'jobApplication' => $this->jobApplication,
                'candidate' => $this->jobApplication->candidate,
                'job' => $this->jobApplication->job,
                'employer' => $this->jobApplication->job->employer,
                'user' => $this->jobApplication->candidate->user,
            ]
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
