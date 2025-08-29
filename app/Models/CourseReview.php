<?php

namespace App\Models\CourseReview;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;

class CourseReview extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'rating',
        'would_recommend',
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
