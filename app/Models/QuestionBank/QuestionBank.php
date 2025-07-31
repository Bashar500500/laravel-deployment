<?php

namespace App\Models\QuestionBank;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;

class QuestionBank extends Model
{
    protected $fillable = [
        'course_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questionBankMultipleTypeQuestions(): HasMany
    {
        return $this->hasMany(QuestionBankMultipleTypeQuestion::class, 'question_bank_id');
    }

    public function questionBankTrueOrFalseQuestions(): HasMany
    {
        return $this->hasMany(QuestionBankTrueOrFalseQuestion::class, 'question_bank_id');
    }

    public function questionBankShortAnswerQuestions(): HasMany
    {
        return $this->hasMany(QuestionBankShortAnswerQuestion::class, 'question_bank_id');
    }

    public function questionBankFillInBlankQuestions(): HasMany
    {
        return $this->hasMany(QuestionBankFillInBlankQuestion::class, 'question_bank_id');
    }
}
