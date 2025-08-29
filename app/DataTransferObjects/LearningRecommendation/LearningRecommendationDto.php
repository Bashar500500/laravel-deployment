<?php

namespace App\DataTransferObjects\LearningRecommendation;

use App\Http\Requests\LearningRecommendation\LearningRecommendationRequest;
use App\Enums\LearningRecommendation\LearningRecommendationRecommendationType;
use App\Enums\LearningRecommendation\LearningRecommendationStatus;

class LearningRecommendationDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $gapId,
        public readonly ?LearningRecommendationRecommendationType $recommendationType,
        public readonly ?int $resourceId,
        public readonly ?string $resourceTitle,
        public readonly ?string $resourceProvider,
        public readonly ?string $resourceUrl,
        public readonly ?int $estimatedDurationHours,
        public readonly ?int $priorityOrder,
        public readonly ?LearningRecommendationStatus $status,
    ) {}

    public static function fromIndexRequest(LearningRecommendationRequest $request): LearningRecommendationDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            gapId: null,
            recommendationType: null,
            resourceId: null,
            resourceTitle: null,
            resourceProvider: null,
            resourceUrl: null,
            estimatedDurationHours: null,
            priorityOrder: null,
            status: null,
        );
    }

    public static function fromStoreRequest(LearningRecommendationRequest $request): LearningRecommendationDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            gapId: $request->validated('gap_id'),
            recommendationType: LearningRecommendationRecommendationType::from($request->validated('recommendation_type')),
            resourceId: $request->validated('resource_id'),
            resourceTitle: $request->validated('resource_title'),
            resourceProvider: $request->validated('resource_provider'),
            resourceUrl: $request->validated('resource_url'),
            estimatedDurationHours: $request->validated('estimated_duration_hours'),
            priorityOrder: $request->validated('priority_order'),
            status: LearningRecommendationStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(LearningRecommendationRequest $request): LearningRecommendationDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            gapId: $request->validated('gap_id'),
            recommendationType: $request->validated('recommendation_type') ?
                LearningRecommendationRecommendationType::from($request->validated('recommendation_type')) :
                null,
            resourceId: $request->validated('resource_id'),
            resourceTitle: $request->validated('resource_title'),
            resourceProvider: $request->validated('resource_provider'),
            resourceUrl: $request->validated('resource_url'),
            estimatedDurationHours: $request->validated('estimated_duration_hours'),
            priorityOrder: $request->validated('priority_order'),
            status: $request->validated('status') ?
                LearningRecommendationStatus::from($request->validated('status')) :
                null,
        );
    }
}
