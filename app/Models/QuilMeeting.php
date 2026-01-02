<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class QuilMeeting extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'event_type',
        'event_created_at',
        'quil_meeting_id',
        'meeting_name',
        'start_time',
        'owner_name',
        'participants',
        'ats_record_name',
        'is_private',
        'account_id',
        'team_id',
        'transcription_url',
        'recording_url',
        'action_items_url',
        'database_notes',
        'follow_up_materials',
        'meeting_id',
        'candidate_id',
        'user_id',
        'processing_status',
        'processing_notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'event_created_at' => 'integer',
        'is_private' => 'boolean',
        'participants' => 'array',
        'database_notes' => 'array',
        'follow_up_materials' => 'array',
    ];

    /**
     * Get the scheduled meeting (if linked).
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the candidate (if matched by phone).
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the user (if matched by phone).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted participants list.
     */
    public function getFormattedParticipantsAttribute()
    {
        if (empty($this->participants)) {
            return 'No participants';
        }
        
        return implode(', ', $this->participants);
    }

    /**
     * Check if meeting has been matched to a user.
     */
    public function isMatched()
    {
        return $this->user_id !== null || $this->candidate_id !== null;
    }

    /**
     * Get the summary from database notes.
     */
    public function getSummary()
    {
        if (empty($this->database_notes)) {
            return null;
        }
        
        foreach ($this->database_notes as $note) {
            if (isset($note['name']) && strtolower($note['name']) === 'summary') {
                return $note['note'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'quil_meeting_id',
        'meeting_name',
        'owner_name',
        'processing_status',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'meeting_name',
        'start_time',
        'created_at',
        'updated_at',
    ];
}
