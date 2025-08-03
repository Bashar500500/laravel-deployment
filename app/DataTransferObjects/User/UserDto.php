<?php
namespace App\DataTransferObjects\User;

use App\Http\Requests\User\UserRequest;
use App\Enums\User\UserRole;

class UserDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?UserRole $role,
        public readonly ?string $fcmToken,
    ) {}

    public static function fromIndexRequest(UserRequest $request): UserDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            firstName: null,
            lastName: null,
            email: null,
            password: null,
            role: null,
            fcmToken: null,
        );
    }

    public static function fromStoreRequest(UserRequest $request): UserDto
    {
        return new self(
            courseId: null,
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

    public static function fromUpdateRequest(UserRequest $request): UserDto
    {
        return new self(
            courseId: null,
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
