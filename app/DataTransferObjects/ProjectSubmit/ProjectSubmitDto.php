<?php

namespace App\DataTransferObjects\ProjectSubmit;

use App\Http\Requests\ProjectSubmit\ProjectSubmitRequest;

class ProjectSubmitDto
{
    public function __construct(
        public readonly ?int $projectId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?array $rubricCriterias,
        public readonly ?string $feedback,
        public readonly ?array $files,
    ) {}

    public static function fromIndexRequest(ProjectSubmitRequest $request): ProjectSubmitDto
    {
        return new self(
            projectId: $request->validated('project_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            rubricCriterias: null,
            feedback: null,
            files: null,
        );
    }

    public static function fromUpdateRequest(ProjectSubmitRequest $request): ProjectSubmitDto
    {
        return new self(
            projectId: null,
            currentPage: null,
            pageSize: null,
            rubricCriterias: $request->validated('rubric_criterias'),
            feedback: $request->validated('feedback'),
            files: $request->validated('files'),
        );
    }
}
