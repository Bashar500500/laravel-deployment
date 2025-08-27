<?php

namespace App\Models\Progress;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Progress\ProgressSkillLevel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use App\Models\User\User;

class Progress extends Model
{
    protected $table = 'progresses';

    protected $fillable = [
        'course_id',
        'student_id',
        'progress',
        'modules',
        'last_active',
        'streak',
        'skill_level',
        'upcomig',
    ];

    protected $casts = [
        'skill_level' => ProgressSkillLevel::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
