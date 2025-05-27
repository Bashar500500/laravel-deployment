<?php

namespace App\DataTransferObjects\Message;

use App\Http\Requests\Message\MessageRequest;

class MessageDto
{
    public function __construct(
        public readonly ?int $chatId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $message,
        public readonly ?bool $isRead,
    ) {}

    public static function fromIndexRequest(MessageRequest $request): MessageDto
    {
        return new self(
            chatId: $request->validated('chat_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            message: null,
            isRead: null,
        );
    }

    public static function fromStoreRequest(MessageRequest $request): MessageDto
    {
        return new self(
            chatId: $request->validated('chat_id'),
            currentPage: null,
            pageSize: null,
            message: $request->validated('message'),
            isRead: $request->validated('message') ? $request->validated('message') : null,
        );
    }

    public static function fromUpdateRequest(MessageRequest $request): MessageDto
    {
        return new self(
            chatId: null,
            currentPage: null,
            pageSize: null,
            message: $request->validated('message'),
            isRead: null,
        );
    }
}
