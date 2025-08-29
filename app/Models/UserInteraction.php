<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UserInteraction\UserInteractionInteractionType;

class UserInteraction extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'page_view_id',
        'interaction_type',
    ];

    protected $casts = [
        'interaction_type' => UserInteractionInteractionType::class,
    ];
}
