<?php

namespace App\DataTransferObjects\Course;

use App\Http\Requests\Course\CourseRequest;

class CourseEnrollmentLimitDto
{
    public function __construct(
        public readonly ?bool $enabled,
        public readonly ?int $limit,
    ) {}

    public static function from(CourseRequest $request): CourseEnrollmentLimitDto
    {
        return new self(
            enabled: $request->validated('access_settings.enrollment_limit.enabled'),
            limit: $request->validated('access_settings.enrollment_limit.limit'),
        );
    }
}
