<?php

namespace App\Services\Group;

use App\Factories\Group\GroupRepositoryFactory;
use App\Http\Requests\Group\GroupRequest;
use App\Models\Group\Group;
use App\DataTransferObjects\Group\GroupDto;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;

class GroupService
{
    public function __construct(
        protected GroupRepositoryFactory $factory,
    ) {}

    public function index(GroupRequest $request): object
    {
        $dto = GroupDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexData();
        $repository = $this->factory->make($role[0]);
        return match ($dto->courseId) {
            null => $repository->all($dto, $data),
            default => $repository->allWithFilter($dto),
        };
    }

    public function show(Group $group): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($group->id);
    }

    public function store(GroupRequest $request): object
    {
        $dto = GroupDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto);
    }

    public function update(GroupRequest $request, Group $group): object
    {
        $dto = GroupDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $group->id);
    }

    public function destroy(Group $group): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($group->id);
    }

    public function join(Group $group): void
    {
        $data = $this->prepareJoinAndLeaveData();
        $student = $data['student'];

        if (!is_null($student->userCourseGroups->where('group_id', $group->id)->first()))
        {
            throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupJoinTwice);
        }
        else if ($group->students->count() == $group->capacity_max)
        {
            throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupCapacityMax);
        }

        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->join($group->id, $data);
    }

    public function leave(Group $group): void
    {
        $data = $this->prepareJoinAndLeaveData();
        $student = $data['student'];

        if (is_null($student->userCourseGroups->where('group_id', $group->id)->first()))
        {
            throw CustomException::forbidden(ModelName::Group, ForbiddenExceptionMessage::GroupNotJoined);
        }

        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->leave($group->id, $data);
    }

    public function view(Group $group): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($group->id);
    }

    public function download(Group $group): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($group->id);
    }

    public function destroyAttachment(Group $group): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->deleteAttachment($group->id);
    }

    private function prepareJoinAndLeaveData(): array
    {
        return [
            'student' => Auth::user(),
        ];
    }

    private function prepareIndexData(): array
    {
        return [
            'instructor' => Auth::user(),
        ];
    }
}
