<?php

namespace App\Models\AssessmentTrueOrFalseQuestion;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDifficulty;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Option\Option;

class AssessmentTrueOrFalseQuestion extends Model
{
    protected $fillable = [
        'assessment_id',
        'text',
        'points',
        'difficulty',
        'category',
        'required',
        'correct_answer',
        'labels',
        'settings',
        'feedback',
    ];

    protected $casts = [
        'difficulty' => AssessmentTrueOrFalseQuestionDifficulty::class,
        'labels' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function options(): MorphMany
    {
        return $this->morphMany(Option::class, 'optionable');
    }

    public function option(): MorphOne
    {
        return $this->morphOne(Option::class, 'optionable');
    }
}
