<?php

namespace App\DataTransferObjects\UserActivity;

use App\Http\Requests\UserActivity\UserActivityRequest;
use App\Enums\UserActivity\UserActivityActivityType;

class UserActivityDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?UserActivityActivityType $activityType,
        public readonly ?array $activityData,
    ) {}

    public static function fromRequest(UserActivityRequest $request): UserActivityDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            activityType: UserActivityActivityType::from($request->validated('activity_type')),
            activityData: $request->validated('activity_data'),
        );
    }
}
