<?php

namespace App\Models\UserInteraction;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UserInteraction\UserInteractionInteractionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
