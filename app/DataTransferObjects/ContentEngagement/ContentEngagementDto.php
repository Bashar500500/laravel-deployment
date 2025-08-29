<?php

namespace App\DataTransferObjects\ContentEngagement;

use App\Http\Requests\ContentEngagement\ContentEngagementRequest;
use App\Enums\ContentEngagement\ContentEngagementContentType;
use App\Enums\ContentEngagement\ContentEngagementEngagementType;

class ContentEngagementDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?ContentEngagementContentType $contentType,
        public readonly ?ContentEngagementEngagementType $engagementType,
        public readonly ?string $engagementValue,
        public readonly ?array $engagementData,
    ) {}

    public static function fromRequest(ContentEngagementRequest $request): ContentEngagementDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            contentType: ContentEngagementContentType::from($request->validated('content_type')),
            engagementType: ContentEngagementEngagementType::from($request->validated('engagement_type')),
            engagementValue: $request->validated('engagement_value'),
            engagementData: $request->validated('engagement_data'),
        );
    }
}
