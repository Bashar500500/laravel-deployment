<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseAccessSettingResource extends JsonResource
{
    public static function makeJson(
        CourseResource $courseResource,
    ): array
    {
        return [
            'accessType' => $courseResource->access_settings_access_type,
            'priceHidden' => $courseResource->access_settings_price_hidden == 0 ? 'false' : 'true',
            'isSecret' => $courseResource->access_settings_is_secret == 0 ? 'false' : 'true',
            'enrollmentLimit' => CourseEnrollmentLimitResource::makeJson($courseResource),
        ];
    }
}
