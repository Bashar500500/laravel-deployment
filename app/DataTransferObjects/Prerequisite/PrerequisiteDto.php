<?php

namespace App\DataTransferObjects\Prerequisite;

use App\Http\Requests\Prerequisite\PrerequisiteRequest;
use App\Enums\Prerequisite\PrerequisiteType;
use App\Enums\Prerequisite\PrerequisiteAppliesTo;
use App\Enums\Prerequisite\PrerequisiteCondition;

class PrerequisiteDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?PrerequisiteType $type,
        public readonly ?int $prerequisite,
        public readonly ?PrerequisiteAppliesTo $appliesTo,
        public readonly ?int $requiredFor,
        public readonly ?PrerequisiteCondition $condition,
        public readonly ?bool $allowOverride,
    ) {}

    public static function fromIndexRequest(PrerequisiteRequest $request): PrerequisiteDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            prerequisite: null,
            appliesTo: null,
            requiredFor: null,
            condition: null,
            allowOverride: null,
        );
    }

    public static function fromStoreRequest(PrerequisiteRequest $request): PrerequisiteDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            type: PrerequisiteType::from($request->validated('type')),
            prerequisite: $request->validated('prerequisite'),
            appliesTo: PrerequisiteAppliesTo::from($request->validated('applies_to')),
            requiredFor: $request->validated('required_for'),
            condition: PrerequisiteCondition::from($request->validated('condition')),
            allowOverride: $request->validated('allow_override'),
        );
    }

    public static function fromUpdateRequest(PrerequisiteRequest $request): PrerequisiteDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            type: $request->validated('type') ?
                PrerequisiteType::from($request->validated('type')) :
                null,
            prerequisite: $request->validated('prerequisite'),
            appliesTo: $request->validated('applies_to') ?
                PrerequisiteAppliesTo::from($request->validated('applies_to')) :
                null,
            requiredFor: $request->validated('required_for'),
            condition: $request->validated('condition') ?
                PrerequisiteCondition::from($request->validated('day')) :
                null,
            allowOverride: $request->validated('allow_override'),
        );
    }
}
