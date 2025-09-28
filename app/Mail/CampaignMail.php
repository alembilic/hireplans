<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public EmailCampaign $campaign,
        public User $user
    ) {
        // Eager load any needed relationships
        $this->campaign->load('creator');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replaceVariables($this->campaign->title),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.campaign',
            with: [
                'campaign_name' => $this->campaign->name,
                'email_content' => $this->replaceVariables($this->campaign->email_content),
                'user_name' => $this->user->name,
                'creator_name' => $this->campaign->creator->name ?? 'Team',
            ],
        );
    }

    /**
     * Replace variables in email content with actual user data.
     */
    private function replaceVariables(string $content): string
    {
        // Define available variables
        $variables = [
            '{{name}}' => $this->user->name ?? '',
            '{{first_name}}' => $this->getFirstName($this->user->name ?? ''),
            '{{email}}' => $this->user->email ?? '',
            '{{phone}}' => $this->user->phone ?? '',
            '{{city}}' => $this->user->city ?? '',
            '{{country}}' => $this->user->country ?? '',
            '{{nationality}}' => $this->user->nationality ?? '',
        ];

        // Add candidate-specific variables if candidate profile exists
        if ($this->user->candidate) {
            $variables = array_merge($variables, [
                '{{current_company}}' => $this->user->candidate->current_company ?? '',
                '{{job_title}}' => $this->user->candidate->current_job_title ?? '',
                '{{candidate_ref}}' => $this->user->candidate->candidate_ref ?? '',
            ]);
        }

        // Replace variables in content
        return str_replace(array_keys($variables), array_values($variables), $content);
    }

    /**
     * Extract first name from full name.
     */
    private function getFirstName(string $fullName): string
    {
        $nameParts = explode(' ', trim($fullName));
        return $nameParts[0] ?? '';
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

