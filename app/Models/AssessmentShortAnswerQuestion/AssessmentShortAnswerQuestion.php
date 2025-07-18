<?php

namespace App\Models\AssessmentShortAnswerQuestion;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionDifficulty;
use App\Enums\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionAnswerType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;

class AssessmentShortAnswerQuestion extends Model
{
    protected $fillable = [
        'assessment_id',
        'text',
        'points',
        'difficulty',
        'category',
        'required',
        'answer_type',
        'character_limit',
        'accepted_answers',
        'settings',
        'feedback',
    ];

    protected $casts = [
        'difficulty' => AssessmentShortAnswerQuestionDifficulty::class,
        'answer_type' => AssessmentShortAnswerQuestionAnswerType::class,
        'accepted_answers' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }
}
