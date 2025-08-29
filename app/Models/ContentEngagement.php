<?php

namespace App\Models\ContentEngagement;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ContentEngagement\ContentEngagementContentType;
use App\Enums\ContentEngagement\ContentEngagementEngagementType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function engagementable(): MorphTo
    {
        return $this->morphTo();
    }
}
