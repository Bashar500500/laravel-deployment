<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Auth\AuthService;
use App\Jobs\GlobalServiceHandlerJob;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendPasswordResetCodeRequest;
use App\Http\Requests\Auth\VerifyPasswordResetCodeRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;

class AuthController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AuthService $service
    ) {
        parent::__construct($controller);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = AuthResource::make(
            $this->service->register($request)
        );

        return $this->controller->setFunctionName(FunctionName::Register)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = AuthResource::make(
            $this->service->login($request)
        );

        return $this->controller->setFunctionName(FunctionName::Login)
            ->setModelName(ModelName::User)
            ->setData($data)
            ->successResponse();
    }

    public function logout(): JsonResponse
    {
        $this->service->logout();

        return $this->controller->setFunctionName(FunctionName::Logout)
            ->setModelName(ModelName::User)
            ->setData((object) [])
            ->successResponse();
    }

    public function sendPasswordResetCode(SendPasswordResetCodeRequest $request): JsonResponse
    {
        $passwordResetCode = $this->service->sendPasswordResetCode($request);

        GlobalServiceHandlerJob::dispatch($passwordResetCode);

        return $this->controller->setFunctionName(FunctionName::SendResetCode)
            ->setModelName(ModelName::PasswordReset)
            ->setData((object) [])
            ->successResponse();
    }

    public function verifyPasswordResetCode(VerifyPasswordResetCodeRequest $request): JsonResponse
    {
        $this->service->verifyPasswordResetCode($request);

        return $this->controller->setFunctionName(FunctionName::VerifyResetCode)
            ->setModelName(ModelName::PasswordReset)
            ->setData((object) [])
            ->successResponse();
    }
}
