<?php

namespace App\DataTransferObjects\Course;

use App\Http\Requests\Course\CourseRequest;
use App\Enums\Course\CourseAccessType;

class CourseAccessSettingsDto
{
    public function __construct(
        public readonly ?CourseAccessType $accessType,
        public readonly ?bool $priceHidden,
        public readonly ?bool $isSecret,
        public readonly ?CourseEnrollmentLimitDto $enrollmentLimit,
    ) {}

    public static function from(CourseRequest $request): CourseAccessSettingsDto
    {
        return new self(
            accessType: $request->validated('access_settings.access_type') ?
                CourseAccessType::from($request->validated('access_settings.access_type')) :
                null,
            priceHidden: $request->validated('access_settings.price_hidden'),
            isSecret: $request->validated('access_settings.is_secret'),
            enrollmentLimit: CourseEnrollmentLimitDto::from($request),
        );
    }
}
