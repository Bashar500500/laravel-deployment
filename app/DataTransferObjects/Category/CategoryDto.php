<?php

namespace App\DataTransferObjects\Category;

use App\Http\Requests\Category\CategoryRequest;
use App\Enums\Category\CategoryStatus;
use Illuminate\Http\UploadedFile;

class CategoryDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?CategoryStatus $status,
        public readonly ?string $description,
        public readonly ?UploadedFile $categoryImage,
    ) {}

    public static function fromIndexRequest(CategoryRequest $request): CategoryDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            description: null,
            status: null,
            categoryImage: null,
        );
    }

    public static function fromStoreRequest(CategoryRequest $request): CategoryDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            status: CategoryStatus::from($request->validated('status')),
            description: $request->validated('description'),
            categoryImage: $request->validated('category_image') ?
                UploadedFile::createFromBase($request->validated('category_image')) :
                null,
        );
    }
    public static function fromUpdateRequest(CategoryRequest $request): CategoryDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            status: $request->validated('status') ?
                CategoryStatus::from($request->validated('status')) :
                null,
            description: $request->validated('description'),
            categoryImage: $request->validated('category_image') ?
                UploadedFile::createFromBase($request->validated('category_image')) :
                null,
        );
    }
}
