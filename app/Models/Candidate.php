<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;

class Candidate extends Model
{
    use HasFactory, AsSource, Filterable;

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
}
