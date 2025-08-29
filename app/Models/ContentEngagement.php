<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ContentEngagement\ContentEngagementContentType;
use App\Enums\ContentEngagement\ContentEngagementEngagementType;

class ContentEngagement extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'content_type',
        'engagement_type',
        'engagement_value',
        'engagement_data',
    ];

    protected $casts = [
        'content_type' => ContentEngagementContentType::class,
        'engagement_type' => ContentEngagementEngagementType::class,
        'engagement_data' => 'array',
    ];
}
