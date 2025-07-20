<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class ReferenceCandidateFeedback extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference_id',
        'candidate_id',
        'quality_of_teaching',
        'breadth_of_knowledge',
        'relationship_with_students',
        'communication_with_parents',
        'relationship_with_colleagues',
        'reliability_and_integrity',
        'class_management',
        'embraces_diversity',
        'creativity',
        'time_keeping',
        'safe_positive_workspace',
        'work_with_again',
        'ethical_compromise',
        'child_protection_issues',
        'comments',
        'signed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'candidate_id' => 'integer',
        'completed_at' => 'datetime',
        'safe_positive_workspace' => 'integer',
        // 'candidate_employed_from' => 'datetime',
        // 'candidate_employed_to' => 'datetime',
    ];

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }
}
