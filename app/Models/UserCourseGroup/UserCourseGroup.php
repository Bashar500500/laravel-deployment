<?php

namespace App\Models\UserCourseGroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\Course\Course;
use App\Models\Group\Group;

class UserCourseGroup extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'group_id',
        'student_code',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public static function getOrder($id): int
    {
        return self::where('course_id', $id)
            ->count() + 1;
    }
}
