<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityDiscussionResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        return [
            'enabled' => $learningActivityResource->discussion_enabled == 0 ? 'false' : 'true',
            'moderated' => $learningActivityResource->discussion_moderated == 0 ? 'false' : 'true',
        ];
    }
}
