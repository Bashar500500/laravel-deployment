<?php

namespace App\DataTransferObjects\Project;

use App\Http\Requests\Project\ProjectRequest;
use Illuminate\Support\Carbon;

class ProjectDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $leaderId,
        public readonly ?int $groupId,
        public readonly ?string $name,
        public readonly ?Carbon $startDate,
        public readonly ?Carbon $endDate,
        public readonly ?string $description,
        public readonly ?array $files,
    ) {}

    public static function fromIndexRequest(ProjectRequest $request): ProjectDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            leaderId: null,
            groupId: null,
            name: null,
            startDate: null,
            endDate: null,
            description: null,
            files: null,
        );
    }

    public static function fromStoreRequest(ProjectRequest $request): ProjectDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: null,
            pageSize: null,
            leaderId: $request->validated('leader_id'),
            groupId: $request->validated('group_id'),
            name: $request->validated('name'),
            startDate: Carbon::parse($request->validated('start_date')),
            endDate: Carbon::parse($request->validated('end_date')),
            description: $request->validated('description'),
            files: $request->validated('files'),
        );
    }

    public static function fromUpdateRequest(ProjectRequest $request): ProjectDto
    {
        return new self(
            courseId: null,
            currentPage: null,
            pageSize: null,
            leaderId: null,
            groupId: null,
            name: $request->validated('name'),
            startDate: Carbon::parse($request->validated('start_date')),
            endDate: Carbon::parse($request->validated('end_date')),
            description: $request->validated('description'),
            files: $request->validated('files'),
        );
    }
}
