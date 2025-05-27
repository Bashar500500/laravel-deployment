<?php

namespace App\DataTransferObjects\SubCategory;

use App\Http\Requests\SubCategory\SubCategoryRequest;
use App\Enums\SubCategory\SubCategoryStatus;
use Illuminate\Http\UploadedFile;

class SubCategoryDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $categoryId,
        public readonly ?string $name,
        public readonly ?SubCategoryStatus $status,
        public readonly ?string $description,
        public readonly ?UploadedFile $subCategoryImage,
    ) {}

    public static function fromIndexRequest(SubCategoryRequest $request): SubCategoryDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            categoryId: null,
            name: null,
            description: null,
            status: null,
            subCategoryImage: null,
        );
    }

    public static function fromStoreRequest(SubCategoryRequest $request): SubCategoryDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            categoryId: $request->validated('category_id'),
            name: $request->validated('name'),
            status: SubCategoryStatus::from($request->validated('status')),
            description: $request->validated('description'),
            subCategoryImage: $request->validated('sub_category_image') ?
                UploadedFile::createFromBase($request->validated('sub_category_image')) :
                null,
        );
    }
    public static function fromUpdateRequest(SubCategoryRequest $request): SubCategoryDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            categoryId: null,
            name: $request->validated('name'),
            status: $request->validated('status') ?
                SubCategoryStatus::from($request->validated('status')) :
                null,
            description: $request->validated('description'),
            subCategoryImage: $request->validated('sub_category_image') ?
                UploadedFile::createFromBase($request->validated('sub_category_image')) :
                null,
        );
    }
}
