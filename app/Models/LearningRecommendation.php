<?php

namespace App\Models\LearningRecommendation;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LearningRecommendation\LearningRecommendationRecommendationType;
use App\Enums\LearningRecommendation\LearningRecommendationStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\LearningGap\LearningGap;
use App\models\Course\Course;

class LearningRecommendation extends Model
{
    protected $fillable = [
        'course_id',
        'gap_id',
        'recommendation_type',
        'resource_id',
        'resource_title',
        'resource_provider',
        'resource_url',
        'estimated_duration_hours',
        'priority_order',
        'status',
    ];

    protected $casts = [
        'recommendation_type' => LearningRecommendationRecommendationType::class,
        'status' => LearningRecommendationStatus::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function gap(): BelongsTo
    {
        return $this->belongsTo(LearningGap::class, 'gap_id');
    }
}
