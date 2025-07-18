<?php

namespace App\DataTransferObjects\AssignmentSubmit;

use App\Http\Requests\AssignmentSubmit\AssignmentSubmitRequest;

class AssignmentSubmitDto
{
    public function __construct(
        public readonly ?int $assignmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $score,
        public readonly ?string $feedback,
    ) {}

    public static function fromIndexRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: $request->validated('assignment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            score: null,
            feedback: null,
        );
    }

    public static function fromUpdateRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: null,
            currentPage: null,
            pageSize: null,
            score: $request->validated('score'),
            feedback: $request->validated('feedback'),
        );
    }
}
