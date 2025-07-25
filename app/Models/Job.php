<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;

class Job extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_ref',
        'employer_id',
        'title',
        'slug',
        'details',
        'location',
        'salary',
        'job_type',
        'category',
        'experience_level',
        'application_deadline',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'application_deadline' => 'date',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the meetings for the job.
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * Check if the user can apply for a job.
     */
    public function canApply() {
        $candidateId = Auth::user()->candidate->id ?? null;

        if (!$candidateId) {
            return false;
        }

        if (!Auth::user()->hasAccess('job.apply')) {
            return false;
        }

        return !$this->jobApplications()->where('candidate_id', $candidateId)->exists();
    }

    /**
     *
     */
    public function candidateProfileRequired() {
        if (!Auth::user()->hasAccess('job.apply')) {
            return false;
        }

        return (bool) !Auth::user()->candidate;
    }

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'title'      => Like::class,
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
        'title',
        'job_ref',
        'location',
        'updated_at',
        'created_at',
        'employer_name',
        'employer_ref',
        'country',
        'user_name',
    ];
}
