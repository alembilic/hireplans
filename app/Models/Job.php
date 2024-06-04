<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

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
}
