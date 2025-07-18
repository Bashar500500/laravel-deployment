<?php

namespace App\DataTransferObjects\AssessmentSubmit;

use App\Http\Requests\AssessmentSubmit\AssessmentSubmitRequest;

class AssessmentSubmitDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $feedback,
    ) {}

    public static function fromIndexRequest(AssessmentSubmitRequest $request): AssessmentSubmitDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            feedback: null,
        );
    }

    public static function fromUpdateRequest(AssessmentSubmitRequest $request): AssessmentSubmitDto
    {
        return new self(
            assessmentId: null,
            currentPage: null,
            pageSize: null,
            feedback: $request->validated('feedback'),
        );
    }
}
