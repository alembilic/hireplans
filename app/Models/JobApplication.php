<?php

namespace App\Models;

use App\Helpers\HelperFunc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;

class JobApplication extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_ref',
        'job_id',
        'candidate_id',
        'cv',
        'cover_letter',
        'notes',
        'status',
        'viewed_at',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'job_id'     => Where::class,
        'candidate_id' => Where::class,
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
        'job_id',
        'candidate_id',
        'updated_at',
        'created_at',
        'job_title',
        'candidate_name',
        'application_ref',
        'status',
        'employer_name',
        // 'country',
        // 'user_name',
    ];

    /**
     * Get the job that owns the job application.
     */
    public function job() {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the employer that owns the job application.
     */
    public function employer() {
        return $this->belongsToThrough(Employer::class, Job::class);
    }

    /**
     * Get the candidate that owns the job application.
     */
    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the URLs for the CV attachment.
     *
     * @return \stdClass
     */
    public function getCv()
    {
        $cvAttachment = $this->candidate->attachment()->wherePivot('attachment_id', $this->cv)->first();
        $cv = HelperFunc::getAttachmentInfo($cvAttachment);
        // dd($cv);
        return $cv;
    }

    /**
     * Get the URLs for the cover letter attachment.
     *
     * @return \stdClass|null
     */
    public function getCoverLetter()
    {
        // dd($this->cover_letter);
        $clAttachment = Attachment::find($this->cover_letter);
        // dd($clAttachment);
        if (!$clAttachment) {
            return null;
        }

        $cl = HelperFunc::getAttachmentInfo($clAttachment);
        // dd($cl);
        return $cl;
    }
}
