<?php

namespace App\DataTransferObjects\Auth;

use App\Http\Requests\Auth\SocialLoginRequest;

class SocialLoginDto
{
    public function __construct(
        public ?string $provider,
        public ?string $token,
        public string $role,
    ) {}

    public static function fromSocialLoginRequest(SocialLoginRequest $request): self
    {
        return new self(
            provider: $request->validated('provider'),
            token: $request->validated('token'),
            role: $request->validated('role'),
        );
    }
}