<?php

namespace App\Models\QuestionBankMultipleTypeQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\QuestionBank\QuestionBank;
use App\Enums\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionType;
use App\Enums\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionDifficulty;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Option\Option;
use App\Models\AssessmentQuestionBankQuestion\AssessmentQuestionBankQuestion;

class QuestionBankMultipleTypeQuestion extends Model
{
    protected $fillable = [
        'question_bank_id',
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
        'type' => QuestionBankMultipleTypeQuestionType::class,
        'difficulty' => QuestionBankMultipleTypeQuestionDifficulty::class,
        'additional_settings' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function options(): MorphMany
    {
        return $this->morphMany(Option::class, 'optionable');
    }

    public function option(): MorphOne
    {
        return $this->morphOne(Option::class, 'optionable');
    }

    public function assessmentQuestionBankQuestions(): MorphMany
    {
        return $this->morphMany(AssessmentQuestionBankQuestion::class, 'questionable');
    }

    public function assessmentQuestionBankQuestion(): MorphOne
    {
        return $this->morphOne(AssessmentQuestionBankQuestion::class, 'questionable');
    }
}
