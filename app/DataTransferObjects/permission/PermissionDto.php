<?php
namespace App\DataTransferObjects\Permission;

use App\Http\Requests\Permission\PermissionRequest;
use App\Enums\User\UserRole;
use App\Enums\Permission\PermissionGuardName;

class PermissionDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?PermissionGuardName $guardName,
        public readonly ?UserRole $role,
    ) {}

    public static function fromindexRequest(PermissionRequest $request): PermissionDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            guardName: null,
            role: null,
        );
    }

    public static function fromStoreRequest(PermissionRequest $request): PermissionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            guardName: PermissionGuardName::from($request->validated('guard_name')),
            role: UserRole::from($request->validated('role')),
        );
    }

    public static function fromUpdateRequest(PermissionRequest $request): PermissionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            guardName: PermissionGuardName::from($request->validated('guard_name')),
            role: UserRole::from($request->validated('role')),
        );
    }
}
