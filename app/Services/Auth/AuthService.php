<?php

namespace App\Services\Auth;

use App\Repositories\Auth\RegisterRepositoryInterface;
use App\Repositories\Auth\PasswordResetCodeRepositoryInterface;
use App\Factories\User\UserRepositoryFactory;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendPasswordResetCodeRequest;
use App\Http\Requests\Auth\VerifyPasswordResetCodeRequest;
use App\Models\Auth\PasswordResetCode;
use App\DataTransferObjects\Auth\RegisterDto;
use App\DataTransferObjects\Auth\LoginDto;
use App\DataTransferObjects\Auth\PasswordResetCodeDto;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AuthService
{
    public function __construct(
        protected RegisterRepositoryInterface $registerRepositoryInterface,
        protected PasswordResetCodeRepositoryInterface $passwordResetRepository,
        protected UserRepositoryFactory $userRepositoryFactory,
    ) {}

    public function register(RegisterRequest $request): object
    {
        $dto = RegisterDto::fromRegisterRequest($request);
        $user = $this->registerRepositoryInterface->create($dto);
        $user['token'] = $user->createToken('api_token')->accessToken;
        $user['role']= $user->getRoleNames();

        return (object) $user;
    }

    public function login(LoginRequest $request): object
    {
        $dto = LoginDto::fromLoginRequest($request);

        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw CustomException::unauthorized(ModelName::User);
        }

        $user = Auth::user();
        $user['token'] = $user->createToken('api_token')->accessToken;
        $user['role']= $user->getRoleNames();

        return (object) $user;
    }

    public function logout(): void
    {
        Auth::user()->token()->revoke();
    }

    public function sendPasswordResetCode(SendPasswordResetCodeRequest $request): object
    {
        $dto = PasswordResetCodeDto::fromSendResetPasswordCodeRequest($request);
        $code = rand(100000, 999999);

        return $this->passwordResetRepository->updateOrCreate($dto);
    }

    public function verifyPasswordResetCode(VerifyPasswordResetCodeRequest $request): void
    {
        $dto = PasswordResetCodeDto::fromVerifyResetPasswordCodeRequest($request);
        $passwordResetCode = PasswordResetCode::where('email', $dto->email)
            ->where('code', $dto->code)
            ->first();

        if (!$passwordResetCode || Carbon::parse($passwordResetCode->created_at)->addDay()->isPast())
        {
            throw CustomException::forbidden(ModelName::PasswordResetCode, ForbiddenExceptionMessage::PasswordResetCode);
        }

        $role = Auth::user()->getRoleNames();
        $repository = $this->userRepositoryFactory->make($role[0]);
        $repository->resetPassword($dto);

        $this->passwordResetRepository->delete($passwordResetCode->id);
    }
}
