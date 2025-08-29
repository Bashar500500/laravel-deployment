<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'rating',
        'would_recommend',
    ];
}
