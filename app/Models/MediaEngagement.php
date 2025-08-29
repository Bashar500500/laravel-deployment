<?php

namespace App\Models\MediaEngagement;

use Illuminate\Database\Eloquent\Model;
use App\Enums\MediaEngagement\MediaEngagementMediaType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
