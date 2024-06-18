<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;

class JobApplication extends Model
{
    use HasFactory, AsSource, Filterable;

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
     * Get the job that owns the job application.
     */
    public function job() {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the candidate that owns the job application.
     */
    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }
}
