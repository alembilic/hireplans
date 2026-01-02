<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Activity extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id',
        'activity_type',
        'title',
        'description',
        'metadata',
        'created_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Activity type constants
     */
    const TYPE_PROFILE_CREATED = 'profile_created';
    const TYPE_JOB_APPLIED = 'job_applied';
    const TYPE_APPLICATION_STATUS_CHANGED = 'application_status_changed';
    const TYPE_MEETING_SCHEDULED = 'meeting_scheduled';
    const TYPE_MEETING_UPDATED = 'meeting_updated';
    const TYPE_MEETING_STATUS_CHANGED = 'meeting_status_changed';
    const TYPE_MEETING_COMPLETED = 'meeting_completed';
    const TYPE_NOTE_ADDED = 'note_added';
    const TYPE_PROFILE_UPDATED = 'profile_updated';
    const TYPE_REFERENCE_REQUESTED = 'reference_requested';
    const TYPE_REFERENCE_COMPLETED = 'reference_completed';
    const TYPE_DOCUMENT_UPLOADED = 'document_uploaded';
    const TYPE_CAMPAIGN_SENT = 'campaign_sent';
    const TYPE_CAMPAIGN_DELIVERED = 'campaign_delivered';
    const TYPE_CAMPAIGN_FAILED = 'campaign_failed';
    const TYPE_QUIL_MEETING_COMPLETED = 'quil_meeting_completed';

    /**
     * Get the candidate that owns the activity.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the user who created the activity.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the activity icon based on type.
     */
    public function getIconAttribute()
    {
        return match($this->activity_type) {
            self::TYPE_PROFILE_CREATED => 'bi-person-plus',
            self::TYPE_JOB_APPLIED => 'bi-briefcase',
            self::TYPE_APPLICATION_STATUS_CHANGED => 'bi-arrow-up-right-circle',
            self::TYPE_MEETING_SCHEDULED => 'bi-calendar-plus',
            self::TYPE_MEETING_UPDATED => 'bi-calendar-event',
            self::TYPE_MEETING_STATUS_CHANGED => 'bi-arrow-repeat',
            self::TYPE_MEETING_COMPLETED => 'bi-calendar-check',
            self::TYPE_NOTE_ADDED => 'bi-chat-text',
            self::TYPE_PROFILE_UPDATED => 'bi-person-gear',
            self::TYPE_REFERENCE_REQUESTED => 'bi-envelope',
            self::TYPE_REFERENCE_COMPLETED => 'bi-envelope-check',
            self::TYPE_DOCUMENT_UPLOADED => 'bi-file-earmark-plus',
            self::TYPE_CAMPAIGN_SENT => 'bi-send',
            self::TYPE_CAMPAIGN_DELIVERED => 'bi-send-check',
            self::TYPE_CAMPAIGN_FAILED => 'bi-send-x',
            self::TYPE_QUIL_MEETING_COMPLETED => 'bi-mic',
            default => 'bi-activity',
        };
    }

    /**
     * Get the activity color based on type.
     */
    public function getColorAttribute()
    {
        return match($this->activity_type) {
            self::TYPE_PROFILE_CREATED => 'success',
            self::TYPE_JOB_APPLIED => 'primary',
            self::TYPE_APPLICATION_STATUS_CHANGED => 'info',
            self::TYPE_MEETING_SCHEDULED => 'warning',
            self::TYPE_MEETING_UPDATED => 'warning',
            self::TYPE_MEETING_STATUS_CHANGED => 'info',
            self::TYPE_MEETING_COMPLETED => 'success',
            self::TYPE_NOTE_ADDED => 'secondary',
            self::TYPE_PROFILE_UPDATED => 'info',
            self::TYPE_REFERENCE_REQUESTED => 'primary',
            self::TYPE_REFERENCE_COMPLETED => 'success',
            self::TYPE_DOCUMENT_UPLOADED => 'info',
            self::TYPE_CAMPAIGN_SENT => 'primary',
            self::TYPE_CAMPAIGN_DELIVERED => 'success',
            self::TYPE_CAMPAIGN_FAILED => 'danger',
            self::TYPE_QUIL_MEETING_COMPLETED => 'info',
            default => 'primary',
        };
    }

    /**
     * Scope to get activities for a specific candidate.
     */
    public function scopeForCandidate($query, $candidateId)
    {
        return $query->where('candidate_id', $candidateId);
    }

    /**
     * Scope to get activities ordered by most recent first.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
