<?php

namespace App\DataTransferObjects\InteractiveContent;

use App\Http\Requests\InteractiveContent\InteractiveContentRequest;
use App\Enums\InteractiveContent\InteractiveContentType;
use Illuminate\Http\UploadedFile;

class InteractiveContentDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?InteractiveContentType $type,
        public readonly ?UploadedFile $file,
    ) {}

    public static function fromIndexRequest(InteractiveContentRequest $request): InteractiveContentDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            description: null,
            type: null,
            file: null,
        );
    }

    public static function fromStoreRequest(InteractiveContentRequest $request): InteractiveContentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: InteractiveContentType::from($request->validated('type')),
            file: $request->validated('file') ?
                UploadedFile::createFromBase($request->validated('file')) :
                null,
        );
    }

    public static function fromUpdateRequest(InteractiveContentRequest $request): InteractiveContentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: $request->validated('type') ?
                InteractiveContentType::from($request->validated('type')) :
                null,
            file: $request->validated('file') ?
                UploadedFile::createFromBase($request->validated('file')) :
                null,
        );
    }
}
