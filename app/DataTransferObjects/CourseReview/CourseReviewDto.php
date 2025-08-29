<?php

namespace App\DataTransferObjects\CourseReview;

use App\Http\Requests\CourseReview\CourseReviewRequest;

class CourseReviewDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?float $rating,
        public readonly ?bool $wouldRecommend,
    ) {}

    public static function fromIndexRequest(CourseReviewRequest $request): CourseReviewDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            rating: null,
            wouldRecommend: null,
        );
    }

    public static function fromStoreRequest(CourseReviewRequest $request): CourseReviewDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            rating: $request->validated('rating'),
            wouldRecommend: $request->validated('would_recommend'),
        );
    }

    public static function fromUpdateRequest(CourseReviewRequest $request): CourseReviewDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            rating: $request->validated('rating'),
            wouldRecommend: $request->validated('would_recommend'),
        );
    }
}
