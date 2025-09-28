<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class EmailCampaign extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'name',
        'title',
        'email_content',
        'status',
        'created_by',
        'sent_at',
        'scheduled_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    /**
     * Get the user who created the campaign.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users for this campaign.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'campaign_users')
                    ->withPivot('status', 'sent_at', 'error_message')
                    ->withTimestamps();
    }

    /**
     * Get the campaign users pivot data.
     */
    public function campaignUsers()
    {
        return $this->hasMany(CampaignUser::class);
    }

    /**
     * Check if the campaign is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the campaign has been sent.
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if the campaign is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Get the total number of users in this campaign.
     */
    public function getUserCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * Get the number of successfully sent emails.
     */
    public function getSentCountAttribute(): int
    {
        return $this->campaignUsers()->where('status', 'sent')->count();
    }

    /**
     * Get the number of failed emails.
     */
    public function getFailedCountAttribute(): int
    {
        return $this->campaignUsers()->where('status', 'failed')->count();
    }
} 