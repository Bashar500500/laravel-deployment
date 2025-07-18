<?php

namespace App\Models\ChallengeCourse;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Challenge\Challenge;
use App\Models\Course\Course;

class ChallengeCourse extends Model
{
    protected $fillable = [
        'challenge_id',
        'course_id',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
