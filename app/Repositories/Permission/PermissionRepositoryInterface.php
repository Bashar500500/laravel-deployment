<?php

namespace App\Repositories\Permission;

use App\DataTransferObjects\Permission\PermissionDto;
use App\DataTransferObjects\Permission\PermissionUserDto;
use Spatie\Permission\Models\Role;
use App\Models\User\User;

interface PermissionRepositoryInterface
{
    public function all(PermissionDto $dto): object;

    public function find(int $id): object;

    public function create(PermissionDto $dto): object;

    public function update(PermissionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function getPermissionsByRole(Role $role): object;

    public function getPermissionsByUser(User $user): object;

    public function assignPermissionToUser(PermissionUserDto $dto): object;

    public function revokePermissionFromUser(PermissionUserDto $dto): object;
}
