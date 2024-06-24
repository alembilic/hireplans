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
use Carbon;

class Reference extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id',
        'name',
        'email',
        'phone',
        'relationship',
        'position',
        'company',
        'company_address',
        'completed_at',
        'candidate_position',
        'candidate_employed_from',
        'candidate_employed_to',
        'candidate_job_type',
        'candidate_service_duration',
        'candidate_leaving_reason',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'candidate_id' => 'integer',
        'completed_at' => 'datetime',
        'candidate_employed_from' => 'datetime',
        'candidate_employed_to' => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'name' => Like::class,
        'email' => Like::class,
        'phone' => Like::class,
        'relationship' => Like::class,
        'position' => Like::class,
        'company' => Like::class,
        'company_address' => Like::class,
        'completed_at' => WhereDateStartEnd::class,
        'candidate_position' => Like::class,
        'candidate_job_type' => Like::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
        'email',
        'phone',
        'relationship',
        'position',
        'company',
        'company_address',
        'completed_at',
        'candidate_position',
        'candidate_employed_from',
        'candidate_employed_to',
        'candidate_job_type',
    ];


    /**
     * Get the candidate's employed from date in the required format.
     *
     * @param  string  $value
     * @return string
     */
    public function getCandidateEmployedFromAttribute($value)
    {
        return $value ? Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Get the candidate's employed to date in the required format.
     *
     * @param  string  $value
     * @return string
     */
    public function getCandidateEmployedToAttribute($value)
    {
        return $value ? Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Get the candidate that owns the reference.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function feedback()
    {
        return $this->hasOne(ReferenceCandidateFeedback::class);
    }
}
