<?php

namespace App\DataTransferObjects\Email;

use App\Http\Requests\Email\EmailRequest;

class EmailDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $userId,
        public readonly ?string $subject,
        public readonly ?string $body,
    ) {}

    public static function fromIndexRequest(EmailRequest $request): EmailDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            userId: null,
            subject: null,
            body: null,
        );
    }

    public static function fromStoreRequest(EmailRequest $request): EmailDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            userId: $request->validated('user_id'),
            subject: $request->validated('subject'),
            body: $request->validated('body'),
        );
    }
}
