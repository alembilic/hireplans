<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use App\Helpers\HelperFunc;

class Candidate extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'candidate_ref',
        'gender',
        'current_company',
        'current_job_title',
        'languages',
        'skills',
        'notes',
    ];

    /**
     * Get all of the candidate's attachments.
     */
    // public function attachments()
    // {
    //     return $this->morphToMany(Attachment::class, 'attachmentable', 'attachmentable');
    // }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Get the user that owns the candidate.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
           'id'         => Where::class,
           'user_name'  => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'user_name',
        'candidate_ref',
        'updated_at',
        'created_at',
    ];

    /**
     * Get the CV attachments for the candidate.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCvAttachments()
    {
        return $this->attachment()->wherePivot('field_name', 'cv')->get();
    }

    /**
     * Get the URLs for the CV attachments.
     *
     * @return array
     */
    public function getCvAttachmentsInfo()
    {
        return $this->getCvAttachments()->map(function (Attachment $attachment) {
            return HelperFunc::getAttachmentInfo($attachment);
        })->toArray();
    }

    /**
     * Get the other documents attachments for the candidate.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOtherDocAttachments()
    {
        return $this->attachment()->wherePivot('field_name', 'other-documents')->get();
    }

    /**
     * Get the URLs for the CV attachments.
     *
     * @return array
     */
    public function getOtherDocAttachmentsInfo()
    {
        return $this->getOtherDocAttachments()->map(function (Attachment $attachment) {
            return HelperFunc::getAttachmentInfo($attachment);
        })->toArray();
    }

    /**
     * Get the candidate's full name.
     *
     * @return string
     */
    public function getFullNameAttribute() {
        return $this->user->name;
    }

    /**
     * Get the candidate's email address.
     *
     * @return string
     */
    public function getEmailAttribute() {
        return $this->user->email;
    }

    // public function attachments()
    // {
    //     return $this->belongsToMany(Attachment::class);
    // }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    /**
     * Get the meetings for the candidate.
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * Get the activities for the candidate.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('created_at', 'desc');
    }
}
