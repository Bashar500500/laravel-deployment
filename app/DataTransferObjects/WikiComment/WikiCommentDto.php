<?php

namespace App\DataTransferObjects\WikiComment;

use App\Http\Requests\WikiComment\WikiCommentRequest;

class WikiCommentDto
{
    public function __construct(
        public readonly ?int $wikiId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $comment,
    ) {}

    public static function fromIndexRequest(WikiCommentRequest $request): WikiCommentDto
    {
        return new self(
            wikiId: $request->validated('wiki_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            comment: null,
        );
    }

    public static function fromStoreRequest(WikiCommentRequest $request): WikiCommentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            wikiId: $request->validated('wiki_id'),
            comment: $request->validated('comment'),
        );
    }

    public static function fromUpdateRequest(WikiCommentRequest $request): WikiCommentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            wikiId: null,
            comment: $request->validated('comment'),
        );
    }
}
