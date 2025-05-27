<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupRepositoryInterface;
use App\Http\Requests\Group\GroupRequest;
use App\Models\Group\Group;
use App\DataTransferObjects\Group\GroupDto;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function __construct(
        protected GroupRepositoryInterface $repository,
    ) {}

    public function index(GroupRequest $request): object
    {
        $dto = GroupDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Group $group): object
    {
        return $this->repository->find($group->id);
    }

    public function store(GroupRequest $request): object
    {
        $dto = GroupDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(GroupRequest $request, Group $group): object
    {
        $dto = GroupDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $group->id);
    }

    public function destroy(Group $group): object
    {
        return $this->repository->delete($group->id);
    }

    public function join(Group $group): void
    {
        $data = $this->prepareJoinAndLeaveData();
        $this->repository->join($group->id, $data);
    }

    public function leave(Group $group): void
    {
        $data = $this->prepareJoinAndLeaveData();
        $this->repository->leave($group->id, $data);
    }

    public function view(Group $group): string
    {
        return $this->repository->view($group->id);
    }

    public function download(Group $group): string
    {
        return $this->repository->download($group->id);
    }

    public function destroyAttachment(Group $group): void
    {
        $this->repository->deleteAttachment($group->id);
    }

    private function prepareJoinAndLeaveData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
