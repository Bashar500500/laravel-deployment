<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityMetadataResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        return [
            'difficulty' => $learningActivityResource->metadata_difficulty,
            'keywords' => $learningActivityResource->metadata_keywords,
        ];
    }
}
