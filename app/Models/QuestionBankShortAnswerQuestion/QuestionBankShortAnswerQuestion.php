<?php

namespace App\Models\QuestionBankShortAnswerQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\QuestionBank\QuestionBank;
use App\Enums\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionDifficulty;
use App\Enums\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionAnswerType;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\AssessmentQuestionBankQuestion\AssessmentQuestionBankQuestion;

class QuestionBankShortAnswerQuestion extends Model
{
    protected $fillable = [
        'question_bank_id',
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
        'difficulty' => QuestionBankShortAnswerQuestionDifficulty::class,
        'answer_type' => QuestionBankShortAnswerQuestionAnswerType::class,
        'accepted_answers' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
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
