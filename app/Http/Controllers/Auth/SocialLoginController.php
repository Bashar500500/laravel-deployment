<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Services\Auth\SocialLoginService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User\UserResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;

class SocialLoginController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected SocialLoginService $service,
    ) {
        parent::__construct($controller);
    }
    
    public function __invoke(SocialLoginRequest $request): JsonResponse
    {
        $data = $this->service->handle($request);
    
        return $this->controller
            ->setFunctionName(FunctionName::Login)
            ->setModelName(ModelName::User)
            ->setData((object)[
                'user' => new UserResource($data->user),
                'token' => $data->token,
            ])
            ->successResponse();
    }
}