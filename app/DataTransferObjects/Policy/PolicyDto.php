<?php

namespace App\DataTransferObjects\Policy;

use App\Http\Requests\Policy\PolicyRequest;

class PolicyDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $category,
        public readonly ?string $description,
    ) {}

    public static function fromIndexRequest(PolicyRequest $request): PolicyDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            category: null,
            description: null,
        );
    }

    public static function fromStoreRequest(PolicyRequest $request): PolicyDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            category: $request->validated('category'),
            description: $request->validated('description'),
        );
    }

    public static function fromUpdateRequest(PolicyRequest $request): PolicyDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            category: $request->validated('category'),
            description: $request->validated('description'),
        );
    }
}
