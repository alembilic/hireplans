<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CampaignCandidate extends Pivot
{
    protected $table = 'campaign_candidates';

    protected $fillable = [
        'email_campaign_id',
        'candidate_id',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the email campaign.
     */
    public function emailCampaign()
    {
        return $this->belongsTo(EmailCampaign::class);
    }

    /**
     * Get the candidate.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Check if the email was sent successfully.
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if the email failed to send.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the email is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
} 