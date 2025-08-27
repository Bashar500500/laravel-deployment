<?php

namespace App\Models\Assessment;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Assessment\AssessmentType;
use App\Enums\Assessment\AssessmentStatus;
use App\Models\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestion;
use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;
use App\Models\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestion;
use App\Models\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use App\Models\TimeLimit\TimeLimit;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\Models\AssessmentQuestionBankQuestion\AssessmentQuestionBankQuestion;
use App\Models\Timer\Timer;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Grade\Grade;

class Assessment extends Model
{
    protected $fillable = [
        'course_id',
        'time_limit_id',
        'type',
        'title',
        'description',
        'status',
        'weight',
        'available_from',
        'available_to',
        'attempts_allowed',
        'shuffle_questions',
        'feedback_options',
    ];

    protected $casts = [
        'type' => AssessmentType::class,
        'status' => AssessmentStatus::class,
        'feedback_options' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function timeLimit(): BelongsTo
    {
        return $this->belongsTo(TimeLimit::class, 'time_limit_id');
    }

    public function assessmentMultipleTypeQuestions(): HasMany
    {
        return $this->hasMany(AssessmentMultipleTypeQuestion::class, 'assessment_id');
    }

    public function assessmentTrueOrFalseQuestions(): HasMany
    {
        return $this->hasMany(AssessmentTrueOrFalseQuestion::class, 'assessment_id');
    }

    public function assessmentShortAnswerQuestions(): HasMany
    {
        return $this->hasMany(AssessmentShortAnswerQuestion::class, 'assessment_id');
    }

    public function assessmentFillInBlankQuestions(): HasMany
    {
        return $this->hasMany(AssessmentFillInBlankQuestion::class, 'assessment_id');
    }

    public function assessmentSubmits(): HasMany
    {
        return $this->hasMany(AssessmentSubmit::class, 'assessment_id');
    }

    public function assessmentQuestionBankQuestions(): HasMany
    {
        return $this->hasMany(AssessmentQuestionBankQuestion::class, 'assessment_id');
    }

    public function timers(): HasMany
    {
        return $this->hasMany(Timer::class, 'assessment_id');
    }

    public function assessmentQuestionBankMultipleTypeQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionBankMultipleTypeQuestion::class, AssessmentQuestionBankQuestion::class,
            'assessment_id',
            'id',
            'id',
            'questionable_id'
        )->where('questionable_type', QuestionBankMultipleTypeQuestion::class)
            ->withoutGlobalScopes();
    }

    public function assessmentQuestionBankTrueOrFalseQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionBankTrueOrFalseQuestion::class, AssessmentQuestionBankQuestion::class,
            'assessment_id',
            'id',
            'id',
            'questionable_id'
        )->where('questionable_type', QuestionBankTrueOrFalseQuestion::class)
            ->withoutGlobalScopes();
    }

    public function assessmentQuestionBankShortAnswerQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionBankShortAnswerQuestion::class, AssessmentQuestionBankQuestion::class,
            'assessment_id',
            'id',
            'id',
            'questionable_id'
        )->where('questionable_type', QuestionBankShortAnswerQuestion::class)
            ->withoutGlobalScopes();
    }

    public function assessmentQuestionBankFillInBlankQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionBankFillInBlankQuestion::class, AssessmentQuestionBankQuestion::class,
            'assessment_id',
            'id',
            'id',
            'questionable_id'
        )->where('questionable_type', QuestionBankFillInBlankQuestion::class)
            ->withoutGlobalScopes();
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function grade(): MorphOne
    {
        return $this->morphOne(Grade::class, 'gradeable');
    }
}
