<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;

class CommunityAccessReactionsDto
{
    public function __construct(
        public readonly ?bool $upvoteEnabled,
        public readonly ?bool $likeEnabled,
        public readonly ?bool $shareEnabled,
    ) {}

    public static function from(CommunityAccessRequest $request): CommunityAccessReactionsDto
    {
        return new self(
            upvoteEnabled: $request->validated('course.reactions.upvote_enabled'),
            likeEnabled: $request->validated('course.reactions.like_enabled'),
            shareEnabled: $request->validated('course.reactions.share_enabled'),
        );
    }
}
