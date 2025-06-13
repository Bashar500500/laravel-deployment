<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;

class CommunityAccessPermissionsDto
{
    public function __construct(
        public readonly ?bool $postEnabled,
        public readonly ?bool $pollEnabled,
        public readonly ?bool $commentEnabled,
    ) {}

    public static function from(CommunityAccessRequest $request): CommunityAccessPermissionsDto
    {
        return new self(
            postEnabled: $request->validated('course.permissions.post_enabled'),
            pollEnabled: $request->validated('course.permissions.poll_enabled'),
            commentEnabled: $request->validated('course.permissions.comment_enabled'),
        );
    }
}
