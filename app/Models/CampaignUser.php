<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CampaignUser extends Pivot
{
    protected $table = 'campaign_users';
    
    protected $fillable = [
        'email_campaign_id',
        'user_id',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function emailCampaign()
    {
        return $this->belongsTo(EmailCampaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
} 