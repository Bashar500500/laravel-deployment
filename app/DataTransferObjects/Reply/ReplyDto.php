<?php

namespace App\DataTransferObjects\Reply;

use App\Http\Requests\Reply\ReplyRequest;

class ReplyDto
{
    public function __construct(
        public readonly ?int $messageId,
        public readonly string $reply,
    ) {}

    public static function fromStoreRequest(ReplyRequest $request): ReplyDto
    {
        return new self(
            messageId: $request->validated('message_id'),
            reply: $request->validated('reply'),
        );
    }

    public static function fromUpdateRequest(ReplyRequest $request): ReplyDto
    {
        return new self(
            messageId: null,
            reply: $request->validated('reply'),
        );
    }
}
