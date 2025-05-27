<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityCompletionResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        return [
            'type' => $learningActivityResource->completion_type,
            'data' => $learningActivityResource->completion_data,
        ];
    }
}
