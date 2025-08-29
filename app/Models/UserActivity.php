<?php

namespace App\Models\UserActivity;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UserActivity\UserActivityActivityType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;

class UserActivity extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'activity_type',
        'activity_data',
    ];

    protected $casts = [
        'activity_type' => UserActivityActivityType::class,
        'activity_data' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
