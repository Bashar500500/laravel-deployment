<?php

namespace App\DataTransferObjects\Project;

use App\Http\Requests\Project\ProjectSubmitRequest;

class ProjectSubmitDto
{
    public function __construct(
        public readonly ?int $projectId,
        public readonly ?array $files,
    ) {}

    public static function fromRequest(ProjectSubmitRequest $request): ProjectSubmitDto
    {
        return new self(
            projectId: $request->validated('project_id'),
            files: $request->validated('files'),
        );
    }
}
