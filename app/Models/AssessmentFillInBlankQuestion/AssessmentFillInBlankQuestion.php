<?php

namespace App\Models\AssessmentFillInBlankQuestion;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDifficulty;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Blank\Blank;

class AssessmentFillInBlankQuestion extends Model
{
    protected $fillable = [
        'assessment_id',
        'text',
        'difficulty',
        'category',
        'required',
        'display_options',
        'grading_options',
        'settings',
        'feedback',
    ];

    protected $casts = [
        'difficulty' => AssessmentFillInBlankQuestionDifficulty::class,
        'display_options' => 'array',
        'grading_options' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function blanks(): MorphMany
    {
        return $this->morphMany(Blank::class, 'blankable');
    }

    public function blank(): MorphOne
    {
        return $this->morphOne(Blank::class, 'blankable');
    }
}
