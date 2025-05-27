<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityAvailabilityResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        return [
            'start' => $learningActivityResource->availability_start,
            'end' => $learningActivityResource->availability_end,
            'timezone' => $learningActivityResource->availability_timezone,
        ];
    }
}
