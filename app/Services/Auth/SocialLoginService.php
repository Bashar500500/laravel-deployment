<?php

namespace App\Services\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\DataTransferObjects\Auth\SocialLoginDto;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Models\User\User;
use App\Repositories\User\UserRepository;


class SocialLoginService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function handle(SocialLoginRequest $request): object
{
    // $dto = SocialLoginDto::fromSocialLoginRequest($request);
    // $socialUser = Socialite::driver($dto->provider)->userFromToken($dto->token);

    // $user = $this->userRepository->findByEmail($socialUser->getEmail());

    // if (! $user) {
    //     $user = $this->userRepository->createFromSocial([
    //         'name' => $socialUser->getName(),
    //         'email' => $socialUser->getEmail(),
    //         'password' => Str::random(12),
    //         'role' => $dto->role,
    //     ]);
    // }

    // $token = $user->createToken('api_token')->accessToken;

    return (object)[
        'user' ,
        'token',
    ];
}
}