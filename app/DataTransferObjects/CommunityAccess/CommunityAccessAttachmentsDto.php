<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;

class CommunityAccessAttachmentsDto
{
    public function __construct(
        public readonly ?bool $imagesEnabled,
        public readonly ?bool $videosEnabled,
        public readonly ?bool $filesEnabled,
    ) {}

    public static function from(CommunityAccessRequest $request): CommunityAccessAttachmentsDto
    {
        return new self(
            imagesEnabled: $request->validated('course.attachments.images_enabled'),
            videosEnabled: $request->validated('course.attachments.videos_enabled'),
            filesEnabled: $request->validated('course.attachments.files_enabled'),
        );
    }
}
