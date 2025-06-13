<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Question\QuestionCorrectAnswer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class Question extends Model
{
    protected $fillable = [
        'course_id',
        'category',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'code_snippets',
        'answer_explanation',
    ];

    protected $casts = [
        'correct_answer' => QuestionCorrectAnswer::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
}
