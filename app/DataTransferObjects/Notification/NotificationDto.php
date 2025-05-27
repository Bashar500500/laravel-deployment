<?php

namespace App\DataTransferObjects\Notification;

use App\Enums\Notification\NotificationType;
use App\Http\Requests\Notification\NotificationRequest;

class NotificationDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?NotificationType $type,
        public readonly ?int $issuerId,
        public readonly ?string $title,
        public readonly ?string $body,
    ) {}

    public static function fromIndexRequest(NotificationRequest $request): NotificationDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            issuerId: null,
            title: null,
            body: null,
        );
    }

    public static function fromStoreRequest(NotificationRequest $request): NotificationDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            type: NotificationType::from($request->validated('type')),
            issuerId: $request->validated('issuer_id'),
            title: $request->validated('title'),
            body: $request->validated('body'),
        );
    }
}
