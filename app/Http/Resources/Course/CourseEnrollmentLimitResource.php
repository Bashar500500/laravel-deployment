<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseEnrollmentLimitResource extends JsonResource
{
    public static function makeJson(
        CourseResource $courseResource,
    ): array
    {
        return [
            'enabled' => $courseResource->access_settings_enrollment_limit_enabled == 0 ? 'false' : 'true',
            'limit' => $courseResource->access_settings_enrollment_limit_limit,
        ];
    }
}
