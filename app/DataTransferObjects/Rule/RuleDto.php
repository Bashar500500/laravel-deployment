<?php

namespace App\DataTransferObjects\Rule;

use App\Http\Requests\Rule\RuleRequest;
use App\Enums\Rule\RuleCategory;
use App\Enums\Rule\RuleFrequency;
use App\Enums\Rule\RuleStatus;

class RuleDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?RuleCategory $category,
        public readonly ?int $points,
        public readonly ?RuleFrequency $frequency,
        public readonly ?RuleStatus $status,
    ) {}

    public static function fromIndexRequest(RuleRequest $request): RuleDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            description: null,
            category: null,
            points: null,
            frequency: null,
            status: null,
        );
    }

    public static function fromStoreRequest(RuleRequest $request): RuleDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            category: RuleCategory::from($request->validated('category')),
            points: $request->validated('points'),
            frequency: RuleFrequency::from($request->validated('frequency')),
            status: RuleStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(RuleRequest $request): RuleDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            category: $request->validated('category') ?
                RuleCategory::from($request->validated('category')) :
                null,
            points: $request->validated('points'),
            frequency: $request->validated('frequency') ?
                RuleFrequency::from($request->validated('frequency')) :
                null,
            status: $request->validated('status') ?
                RuleStatus::from($request->validated('status')) :
                null,
        );
    }
}
