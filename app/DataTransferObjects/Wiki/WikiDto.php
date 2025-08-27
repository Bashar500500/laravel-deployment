<?php

namespace App\DataTransferObjects\Wiki;

use App\Http\Requests\Wiki\WikiRequest;
use App\Enums\Wiki\WikiType;

class WikiDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?WikiType $type,
        public readonly ?array $tags,
        public readonly ?array $collaborators,
        public readonly ?array $files,
    ) {}

    public static function fromIndexRequest(WikiRequest $request): WikiDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            description: null,
            type: null,
            tags: null,
            collaborators: null,
            files: null,
        );
    }

    public static function fromStoreRequest(WikiRequest $request): WikiDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: WikiType::from($request->validated('type')),
            tags: $request->validated('tags'),
            collaborators: $request->validated('collaborators'),
            files: $request->validated('files'),
        );
    }

    public static function fromUpdateRequest(WikiRequest $request): WikiDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: $request->validated('type') ?
                WikiType::from($request->validated('type')) :
                null,
            tags: $request->validated('tags'),
            collaborators: $request->validated('collaborators'),
            files: $request->validated('files'),
        );
    }
}
