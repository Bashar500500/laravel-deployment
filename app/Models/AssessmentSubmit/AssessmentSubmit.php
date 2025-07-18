<?php

namespace App\Models\AssessmentSubmit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;
use App\Models\User\User;

class AssessmentSubmit extends Model
{
    protected $fillable = [
        'assessment_id',
        'student_id',
        'score',
        'feedback',
        'detailed_results',
        'answers',
    ];

    protected $casts = [
        'detailed_results' => 'array',
        'answers' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
