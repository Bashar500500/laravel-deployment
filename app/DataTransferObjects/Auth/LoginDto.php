<?php

namespace App\DataTransferObjects\Auth;

use App\Http\Requests\Auth\LoginRequest;

class LoginDto
{
    public function __construct(
        public readonly ?string $email,
        public readonly ?string $password,
    ) {}

    public static function fromLoginRequest(LoginRequest $request): LoginDto
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );
    }
}
