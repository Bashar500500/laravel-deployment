<?php

namespace App\DataTransferObjects\Permission;

use App\Http\Requests\Permission\PermissionUserRequest;

class PermissionUserDto
{
    public function __construct(
        public readonly int $userId,
        public readonly string $permission,
    ) {}

    public static function fromPermissionUserRequest(PermissionUserRequest $request): PermissionUserDto
    {
        return new self(
            userId: $request->validated('user_id'),
            permission: $request->validated('permission'),
        );
    }
}
