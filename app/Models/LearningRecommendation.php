<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LearningRecommendation\LearningRecommendationRecommendationType;
use App\Enums\LearningRecommendation\LearningRecommendationStatus;

class LearningRecommendation extends Model
{
    protected $fillable = [
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
}
