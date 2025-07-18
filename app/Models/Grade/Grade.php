<?php

namespace App\Models\Grade;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeTrend;
use App\Enums\Grade\GradeResubmission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'due_date',
        'extended_due_date',
        'status',
        'points_earned',
        'max_points',
        'percentage',
        'category',
        'class_average',
        'trend',
        'trend_data',
        'feedback',
        'resubmission',
        'resubmission_due',
    ];

    protected $casts = [
        'status' => GradeStatus::class,
        'category' => GradeCategory::class,
        'trend' => GradeTrend::class,
        'trend_data' => 'array',
        'resubmission' => GradeResubmission::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function gradeable(): MorphTo
    {
        return $this->morphTo();
    }
}
