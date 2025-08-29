<?php

namespace App\Models\UserActivity;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UserActivity\UserActivityActivityType;

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
}
