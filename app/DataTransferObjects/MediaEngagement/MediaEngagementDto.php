<?php

namespace App\DataTransferObjects\MediaEngagement;

use App\Http\Requests\MediaEngagement\MediaEngagementRequest;
use App\Enums\MediaEngagement\MediaEngagementMediaType;

class MediaEngagementDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?MediaEngagementMediaType $mediaType,
        public readonly ?int $watchTime,
        public readonly ?float $completionPercentage,
        public readonly ?int $playCount,
        public readonly ?float $engagementScore,
    ) {}

    public static function fromRequest(MediaEngagementRequest $request): MediaEngagementDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            mediaType: MediaEngagementMediaType::from($request->validated('media_type')),
            watchTime: $request->validated('watch_time'),
            completionPercentage: $request->validated('completion_percentage'),
            playCount: $request->validated('play_count'),
            engagementScore: $request->validated('engagement_score'),
        );
    }
}
