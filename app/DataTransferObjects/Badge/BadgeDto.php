<?php

namespace App\DataTransferObjects\Badge;

use App\Http\Requests\Badge\BadgeRequest;
use App\Enums\Badge\BadgeCategory;
use App\Enums\Badge\BadgeSubCategory;
use App\Enums\Badge\BadgeDifficulty;
use App\Enums\Badge\BadgeStatus;

class BadgeDto
{
    public function __construct(
        public readonly ?int $instructorId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?BadgeCategory $category,
        public readonly ?BadgeSubCategory $subCategory,
        public readonly ?BadgeDifficulty $difficulty,
        public readonly ?string $icon,
        public readonly ?string $color,
        public readonly ?string $shape,
        public readonly ?string $imageUrl,
        public readonly ?BadgeStatus $status,
        public readonly ?array $reward,
    ) {}

    public static function fromIndexRequest(BadgeRequest $request): BadgeDto
    {
        return new self(
            instructorId: null,
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            description: null,
            category: null,
            subCategory: null,
            difficulty: null,
            icon: null,
            color: null,
            shape: null,
            imageUrl: null,
            status: null,
            reward: null,
        );
    }

    public static function fromStoreRequest(BadgeRequest $request): BadgeDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            category: BadgeCategory::from($request->validated('category')),
            subCategory: $request->validated('sub_category') ?
                BadgeSubCategory::from($request->validated('sub_category')) :
                null,
            difficulty: BadgeDifficulty::from($request->validated('difficulty')),
            icon: $request->validated('icon'),
            color: $request->validated('color'),
            shape: $request->validated('shape'),
            imageUrl: $request->validated('image_url'),
            status: $request->validated('status') ?
                BadgeStatus::from($request->validated('status')) :
                null,
            reward: $request->validated('reward'),
        );
    }

    public static function fromUpdateRequest(BadgeRequest $request): BadgeDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            category: $request->validated('category') ?
                BadgeCategory::from($request->validated('category')) :
                null,
            subCategory: $request->validated('sub_category') ?
                BadgeSubCategory::from($request->validated('sub_category')) :
                null,
            difficulty: $request->validated('difficulty') ?
                BadgeDifficulty::from($request->validated('difficulty')) :
                null,
            icon: $request->validated('icon'),
            color: $request->validated('color'),
            shape: $request->validated('shape'),
            imageUrl: $request->validated('image_url'),
            status: $request->validated('status') ?
                BadgeStatus::from($request->validated('status')) :
                null,
            reward: $request->validated('reward'),
        );
    }
}
