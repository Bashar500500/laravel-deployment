<?php

namespace App\DataTransferObjects\ReusableContent;

use App\Http\Requests\ReusableContent\ReusableContentRequest;
use App\Enums\ReusableContent\ReusableContentType;
use Illuminate\Http\UploadedFile;

class ReusableContentDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?ReusableContentType $type,
        public readonly ?array $tags,
        public readonly ?bool $shareWithCommunity,
        public readonly ?UploadedFile $file,
    ) {}

    public static function fromIndexRequest(ReusableContentRequest $request): ReusableContentDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            description: null,
            type: null,
            tags: null,
            shareWithCommunity: null,
            file: null,
        );
    }

    public static function fromStoreRequest(ReusableContentRequest $request): ReusableContentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: ReusableContentType::from($request->validated('type')),
            tags: $request->validated('tags'),
            shareWithCommunity: $request->validated('share_with_community'),
            file: $request->validated('file') ?
                UploadedFile::createFromBase($request->validated('file')) :
                null,
        );
    }

    public static function fromUpdateRequest(ReusableContentRequest $request): ReusableContentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            type: $request->validated('type') ?
                ReusableContentType::from($request->validated('type')) :
                null,
            tags: $request->validated('tags'),
            shareWithCommunity: $request->validated('share_with_community'),
            file: $request->validated('file') ?
                UploadedFile::createFromBase($request->validated('file')) :
                null,
        );
    }
}
