<?php

namespace App\DataTransferObjects\Whiteboard;

use App\Http\Requests\Whiteboard\WhiteboardRequest;

class WhiteboardDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?string $name,
    ) {}

    public static function fromIndexRequest(WhiteboardRequest $request): WhiteboardDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            courseId: null,
            name: null,
        );
    }

    public static function fromStoreRequest(WhiteboardRequest $request): WhiteboardDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            name: $request->validated('name'),
        );
    }

    public static function fromUpdateRequest(WhiteboardRequest $request): WhiteboardDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            name: $request->validated('name'),
        );
    }
}
