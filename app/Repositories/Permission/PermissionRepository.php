<?php

namespace App\Repositories\Permission;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User\User;
use App\DataTransferObjects\Permission\PermissionDto;
use App\DataTransferObjects\Permission\PermissionUserDto;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }

    public function all(PermissionDto $dto): object
    {
        return (object) $this->model->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function create(PermissionDto $dto): object
    {
        $permission = DB::transaction(function () use ($dto) {
            $permission = $this->model->create([
                'name' => $dto->name,
                'guard_name' => $dto->guardName,
            ]);

            $role = Role::where('name', $dto->role)
                ->where('guard_name', $dto->guardName)
                ->first();

            $role->givePermissionTo($permission);
            $permission['role'] = $role->name;

            return $permission;
        });
        return $permission;
    }

    public function update(PermissionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        return DB::transaction(function () use ($dto, $model) {
            $permission = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'guard_name' => $dto->guardName ? $dto->guardName : $model->guard_name,
            ]);

            if ($dto->role) {
                foreach ($permission->roles as $role) {
                    $role->revokePermissionTo($permission);
                }

                $role = Role::where('name', $dto->role)
                    ->where('guard_name', $dto->guardName)
                    ->first();

                $role->givePermissionTo($permission);

                $permission['role'] = $role->name;
            }

            return $permission;
        });
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $permission = DB::transaction(function () use ($id, $model) {
            foreach ($model->users as $user) {
                $user->revokePermissionTo($model);
            }

            foreach ($model->roles as $role) {
                $role->revokePermissionTo($model);
            }

            return parent::delete($id);

        });

        return $permission;
    }

    public function getPermissionsByRole(Role $role): object
    {
        return $role->getAllPermissions();
    }

    public function getPermissionsByUser(User $user): object
    {
        return $user->getAllPermissions();
    }

    public function assignPermissionToUser(PermissionUserDto $dto): object
    {
        $permission = DB::transaction(function () use ($dto) {
            $user = User::find($dto->userId);

            if ($user->hasPermissionTo($dto->permission))
            {
                throw CustomException::alreadyExists(ModelName::Permission);
            }

            $permission = $user->givePermissionTo($dto->permission);
            return $permission;
        });

        return $permission;
    }

    public function revokePermissionFromUser(PermissionUserDto $dto): object
    {
        $permission = DB::transaction(function () use ($dto) {
            $user = User::findOrFail($dto->userId);

            if (!$user->hasPermissionTo($dto->permission))
            {
                throw CustomException::notFound('Permission');
            }

            $permission = $user->revokePermissionTo($dto->permission);
            return $permission;
        });

        return $permission;
    }
}
