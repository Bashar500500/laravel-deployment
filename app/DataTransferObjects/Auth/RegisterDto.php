<?php

namespace App\DataTransferObjects\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Enums\User\UserRole;

class RegisterDto
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?UserRole $role,
    ) {}

    public static function fromRegisterRequest(RegisterRequest $request): RegisterDto
    {
        return new self(
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            role: UserRole::from($request->validated('role')),
        );
    }
}
