<?php

namespace App\Models\EnrollmentOption;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EnrollmentOption\EnrollmentOptionType;
use App\Enums\EnrollmentOption\EnrollmentOptionPeriod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;

class EnrollmentOption extends Model
{
    protected $fillable = [
        'course_id',
        'type',
        'period',
        'allow_self_enrollment',
        'enable_waiting_list',
        'require_instructor_approval',
        'require_prerequisites',
        'enable_notifications',
        'enable_emails',
    ];

    protected $casts = [
        'type' => EnrollmentOptionType::class,
        'period' => EnrollmentOptionPeriod::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
