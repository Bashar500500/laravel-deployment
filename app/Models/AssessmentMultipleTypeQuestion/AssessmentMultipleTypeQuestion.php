<?php

namespace App\Models\AssessmentMultipleTypeQuestion;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionType;
use App\Enums\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionDifficulty;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Option\Option;

class AssessmentMultipleTypeQuestion extends Model
{
    protected $fillable = [
        'assessment_id',
        'type',
        'text',
        'points',
        'difficulty',
        'category',
        'required',
        'additional_settings',
        'settings',
        'feedback',
    ];

    protected $casts = [
        'type' => AssessmentMultipleTypeQuestionType::class,
        'difficulty' => AssessmentMultipleTypeQuestionDifficulty::class,
        'additional_settings' => 'array',
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
