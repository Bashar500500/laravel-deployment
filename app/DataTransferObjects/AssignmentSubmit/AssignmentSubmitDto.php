<?php

namespace App\DataTransferObjects\AssignmentSubmit;

use App\Http\Requests\AssignmentSubmit\AssignmentSubmitRequest;

class AssignmentSubmitDto
{
    public function __construct(
        public readonly ?int $assignmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?array $rubricCriterias,
        public readonly ?int $plagiarismScore,
        public readonly ?string $feedback,
        public readonly ?array $files,
    ) {}

    public static function fromIndexRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: $request->validated('assignment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            rubricCriterias: null,
            plagiarismScore: null,
            feedback: null,
            files: null,
        );
    }

    public static function fromUpdateRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: null,
            currentPage: null,
            pageSize: null,
            rubricCriterias: $request->validated('rubric_criterias'),
            plagiarismScore: $request->validated('plagiarism_score'),
            feedback: $request->validated('feedback'),
            files: $request->validated('files'),
        );
    }
}
