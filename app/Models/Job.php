<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'is_active'
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Check if the user can apply for a job.
     */
    public function canApply() {
        $candidateId = auth()->user()->candidate->id;

        return !$this->jobApplications()->where('candidate_id', $candidateId)->exists();
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
