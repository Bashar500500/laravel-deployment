<?php

namespace App\DataTransferObjects\UserInteraction;

use App\Http\Requests\UserInteraction\UserInteractionRequest;
use App\Enums\UserInteraction\UserInteractionInteractionType;

class UserInteractionDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?int $pageViewId,
        public readonly ?UserInteractionInteractionType $interactionType,
    ) {}

    public static function fromRequest(UserInteractionRequest $request): UserInteractionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            pageViewId: $request->validated('page_view_id'),
            interactionType: UserInteractionInteractionType::from($request->validated('interaction_type')),
        );
    }
}
