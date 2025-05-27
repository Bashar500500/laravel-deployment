<?php

namespace App\DataTransferObjects\Auth;

use App\Http\Requests\Auth\SendPasswordResetCodeRequest;
use App\Http\Requests\Auth\VerifyPasswordResetCodeRequest;

class PasswordResetCodeDto
{
    public function __construct(
        public ?string $email,
        public ?string $code,
        public ?string $password
    ) {}

    public static function fromSendResetPasswordCodeRequest(SendPasswordResetCodeRequest $request): PasswordResetCodeDto
    {
        return new self(
            email: $request->validated('email'),
            code: null,
            password: null,
        );
    }

    public static function fromVerifyResetPasswordCodeRequest(VerifyPasswordResetCodeRequest $request): PasswordResetCodeDto
    {
        return new self(
            email: $request->validated('email'),
            code: $request->validated('code'),
            password: $request->validated('password'),
        );
    }
}
