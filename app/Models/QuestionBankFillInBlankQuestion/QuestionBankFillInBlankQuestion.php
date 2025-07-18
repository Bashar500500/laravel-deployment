<?php

namespace App\Models\QuestionBankFillInBlankQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\QuestionBank\QuestionBank;
use App\Enums\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionDifficulty;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Blank\Blank;
use App\Models\AssessmentQuestionBankQuestion\AssessmentQuestionBankQuestion;

class QuestionBankFillInBlankQuestion extends Model
{
    protected $fillable = [
        'question_bank_id',
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
        'difficulty' => QuestionBankFillInBlankQuestionDifficulty::class,
        'display_options' => 'array',
        'grading_options' => 'array',
        'settings' => 'array',
        'feedback' => 'array',
    ];

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function blanks(): MorphMany
    {
        return $this->morphMany(Blank::class, 'blankable');
    }

    public function blank(): MorphOne
    {
        return $this->morphOne(Blank::class, 'blankable');
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
