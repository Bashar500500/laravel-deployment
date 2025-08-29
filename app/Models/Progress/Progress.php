<?php

namespace App\Models\Progress;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Progress\ProgressSkillLevel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\UserSession\UserSession;

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

    public function sessions(): MorphMany
    {
        return $this->morphMany(UserSession::class, 'sessionable');
    }

    public function session(): MorphOne
    {
        return $this->morphOne(UserSession::class, 'sessionable');
    }
}
