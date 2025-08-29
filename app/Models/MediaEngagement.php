<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\MediaEngagement\MediaEngagementMediaType;

class MediaEngagement extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'media_type',
        'watch_time',
        'completion_percentage',
        'play_count',
        'engagement_score',
    ];

    protected $casts = [
        'media_type' => MediaEngagementMediaType::class,
    ];
}
