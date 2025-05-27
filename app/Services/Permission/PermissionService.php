<?php

namespace App\Services\Permission;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Http\Requests\Permission\PermissionUserRequest;
use App\Http\Requests\Permission\PermissionRequest;
use App\Models\User\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\Permission\PermissionDto;
use App\DataTransferObjects\Permission\PermissionUserDto;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $repository
    ) {}

    public function index(PermissionRequest $request): object
    {
        $dto = PermissionDto::fromindexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Permission $permission): object
    {
        return $this->repository->find($permission->id);
    }

    public function create(PermissionRequest $request): object
    {
        $dto = PermissionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(PermissionRequest $request, Permission $permission): object
    {
        $dto = PermissionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $permission->id);
    }
    public function destroy(Permission $permission): object
    {
        return $this->repository->delete($permission->id);
    }

    public function getPermissionsByRole(Role $role): object
    {
        return $this->repository->getPermissionsByRole($role);
    }

    public function getPermissionsByUser(User $user): object
    {
        return $this->repository->getPermissionsByUser($user);
    }

    public function assignPermissionToUser(PermissionUserRequest $request)
    {
        $dto = PermissionUserDto::fromPermissionUserRequest($request);
        return $this->repository->assignPermissionToUser($dto);
    }

    public function revokePermissionFromUser(PermissionUserRequest $request)
    {
        $dto = PermissionUserDto::fromPermissionUserRequest($request);
        return $this->repository->revokePermissionFromUser($dto);
    }
}
