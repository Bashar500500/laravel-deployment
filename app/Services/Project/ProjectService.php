<?php

namespace App\Services\Project;

use App\Repositories\Project\ProjectRepositoryInterface;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project\Project;
use App\DataTransferObjects\Project\ProjectDto;
use App\Models\User\User;
use App\Models\Group\Group;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $repository,
    ) {}

    public function index(ProjectRequest $request): object
    {
        $dto = ProjectDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Project $project): object
    {
        return $this->repository->find($project->id);
    }

    public function store(ProjectRequest $request): object
    {
        $dto = ProjectDto::fromStoreRequest($request);

        $leader = User::find($dto->leaderId);
        if (is_null($leader->userCourseGroups->where('student_id', $dto->leaderId)
            ->where('course_id', $dto->courseId)->first()))
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectLeaderNotInCourse);
        }

        $group = Group::find($dto->groupId);
        if ($group->course_id != $dto->courseId)
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectGroupNotInCourse);
        }

        return $this->repository->create($dto);
    }

    public function update(ProjectRequest $request, Project $project): object
    {
        $dto = ProjectDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $project->id);
    }

    public function destroy(Project $project): object
    {
        return $this->repository->delete($project->id);
    }

    public function view(Project $project, string $fileName): string
    {
        return $this->repository->view($project->id, $fileName);
    }

    public function download(Project $project): string
    {
        return $this->repository->download($project->id);
    }

    public function destroyAttachment(Project $project, string $fileName): void
    {
        $this->repository->deleteAttachment($project->id, $fileName);
    }
}
