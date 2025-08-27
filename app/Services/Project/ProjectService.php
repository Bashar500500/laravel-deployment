<?php

namespace App\Services\Project;

use App\Factories\Project\ProjectRepositoryFactory;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project\Project;
use App\DataTransferObjects\Project\ProjectDto;
use App\DataTransferObjects\Project\ProjectSubmitDto;
use App\Models\User\User;
use App\Models\Group\Group;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Http\Requests\Project\ProjectSubmitRequest;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryFactory $factory,
    ) {}

    public function index(ProjectRequest $request): object
    {
        $dto = ProjectDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto);
    }

    public function show(Project $project): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($project->id);
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

        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto);
    }

    public function update(ProjectRequest $request, Project $project): object
    {
        $dto = ProjectDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $project->id);
    }

    public function destroy(Project $project): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($project->id);
    }

    public function view(Project $project, string $fileName): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($project->id, $fileName);
    }

    public function download(Project $project): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($project->id);
    }

    public function destroyAttachment(Project $project, string $fileName): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->deleteAttachment($project->id, $fileName);
    }

    public function submit(ProjectSubmitRequest $request): object
    {
        $dto = ProjectSubmitDto::fromRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->submit($dto);
    }
}
