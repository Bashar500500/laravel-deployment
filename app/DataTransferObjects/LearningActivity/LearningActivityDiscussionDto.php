<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;

class LearningActivityDiscussionDto
{
    public function __construct(
        public readonly ?bool $enabled,
        public readonly ?bool $moderated,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityDiscussionDto
    {
        return new self(
            enabled: $request->validated('discussion.enabled'),
            moderated: $request->validated('discussion.moderated'),
        );
    }
}
