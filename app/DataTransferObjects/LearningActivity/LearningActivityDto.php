<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;
use App\Enums\LearningActivity\LearningActivityType;
use App\Enums\LearningActivity\LearningActivityStatus;

class LearningActivityDto
{
    public function __construct(
        public readonly ?int $sectionId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?LearningActivityType $type,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?LearningActivityStatus $status,
        public readonly ?LearningActivityFlagsDto $learningActivityFlagsDto,
        public readonly ?LearningActivityContentDto $learningActivityContentDto,
        public readonly ?string $thumbnailUrl,
        public readonly ?LearningActivityCompletionDto $learningActivityCompletionDto,
        public readonly ?LearningActivityAvailabilityDto $learningActivityAvailabilityDto,
        public readonly ?LearningActivityDiscussionDto $learningActivityDiscussionDto,
        public readonly ?LearningActivityMetadataDto $learningActivityMetadataDto,
    ) {}

    public static function fromIndexRequest(LearningActivityRequest $request): LearningActivityDto
    {
        return new self(
            sectionId: $request->validated('section_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            title: null,
            description: null,
            status: null,
            learningActivityFlagsDto: null,
            learningActivityContentDto: null,
            thumbnailUrl: null,
            learningActivityCompletionDto: null,
            learningActivityAvailabilityDto: null,
            learningActivityDiscussionDto: null,
            learningActivityMetadataDto: null,
        );
    }

    public static function fromStoreRequest(LearningActivityRequest $request): LearningActivityDto
    {
        return new self(
            sectionId: $request->validated('section_id'),
            currentPage: null,
            pageSize: null,
            type: LearningActivityType::from($request->validated('type')),
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status') ?
                LearningActivityStatus::from($request->validated('status')) :
                null,
            learningActivityFlagsDto: LearningActivityFlagsDto::from($request),
            learningActivityContentDto: LearningActivityContentDto::from($request),
            thumbnailUrl: $request->validated('thumbnailUrl'),
            learningActivityCompletionDto: LearningActivityCompletionDto::from($request),
            learningActivityAvailabilityDto: LearningActivityAvailabilityDto::from($request),
            learningActivityDiscussionDto: LearningActivityDiscussionDto::from($request),
            learningActivityMetadataDto: LearningActivityMetadataDto::from($request),
        );
    }

    public static function fromUpdateRequest(LearningActivityRequest $request): LearningActivityDto
    {
        return new self(
            sectionId: null,
            currentPage: null,
            pageSize: null,
            type: $request->validated('type') ?
                LearningActivityType::from($request->validated('type')) :
                null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status') ?
                LearningActivityStatus::from($request->validated('status')) :
                null,
            learningActivityFlagsDto: LearningActivityFlagsDto::from($request),
            learningActivityContentDto: LearningActivityContentDto::from($request),
            thumbnailUrl: $request->validated('thumbnailUrl'),
            learningActivityCompletionDto: LearningActivityCompletionDto::from($request),
            learningActivityAvailabilityDto: LearningActivityAvailabilityDto::from($request),
            learningActivityDiscussionDto: LearningActivityDiscussionDto::from($request),
            learningActivityMetadataDto: LearningActivityMetadataDto::from($request),
        );
    }
}
