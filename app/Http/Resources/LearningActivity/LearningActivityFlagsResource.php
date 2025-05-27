<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityFlagsResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        return [
            'isFreePreview' => $learningActivityResource->flags_is_free_preview == 0 ? 'false' : 'true',
            'isCompulsory' => $learningActivityResource->flags_is_compulsory == 0 ? 'false' : 'true',
            'requiresEnrollment' => $learningActivityResource->flags_requires_enrollment == 0 ? 'false' : 'true',
        ];
    }
}
