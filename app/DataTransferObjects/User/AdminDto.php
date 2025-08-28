<?php
namespace App\DataTransferObjects\User;

use App\Http\Requests\User\AdminRequest;
use App\Enums\User\UserRole;

class AdminDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?UserRole $role,
        public readonly ?string $fcmToken,
    ) {}

    public static function fromIndexRequest(AdminRequest $request): AdminDto
    {
        return new self(
            role: $request->validated('role') ?
                UserRole::from($request->validated('role')) :
                null,
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            firstName: null,
            lastName: null,
            email: null,
            password: null,
            fcmToken: null,
        );
    }

    public static function fromStoreRequest(AdminRequest $request): AdminDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            role: UserRole::from($request->validated('role')),
            fcmToken: $request->validated('fcm_token'),
        );
    }

    public static function fromUpdateRequest(AdminRequest $request): AdminDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            role: null,
            fcmToken: $request->validated('fcm_token'),
        );
    }
}
