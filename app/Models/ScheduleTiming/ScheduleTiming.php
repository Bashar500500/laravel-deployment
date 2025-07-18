<?php

namespace App\Models\ScheduleTiming;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\InstructorAvailableTiming\InstructorAvailableTiming;

class ScheduleTiming extends Model
{
    protected $fillable = [
        'instructor_id',
        'course_id',
        'instructor_available_timings',
    ];

    protected $casts = [
        'instructor_available_timings' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
