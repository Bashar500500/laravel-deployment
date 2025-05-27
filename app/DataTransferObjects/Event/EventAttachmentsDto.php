<?php

namespace App\DataTransferObjects\Event;

use App\Http\Requests\Event\EventRequest;

class EventAttachmentsDto
{
    public function __construct(
        public readonly ?array $files,
        public readonly ?array $links,
    ) {}

    public static function from(EventRequest $request): EventAttachmentsDto
    {
        return new self(
            files: $request->validated('attachments.files'),
            links: $request->validated('attachments.links'),
        );
    }
}
