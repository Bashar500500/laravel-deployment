<?php

namespace App\Http\Resources\LearningRecommendation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningRecommendationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'courseId' => $this->course_id,
            'gapId' => $this->gap_id,
            'recommendationType' => $this->recommendation_type,
            'resourceId' => $this->resource_id,
            'resourceTitle' => $this->resource_title,
            'resourceProvider' => $this->resource_provider,
            'resourceUrl' => $this->resource_url,
            'estimatedDurationHours' => $this->estimated_duration_hours,
            'priorityOrder' => $this->priority_order,
            'status' => $this->status,
        ];
    }
}
