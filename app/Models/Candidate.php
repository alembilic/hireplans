<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Candidate extends Model
{
    use HasFactory, AsSource;

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
