<?php

namespace App\Http\Controllers\UserActivity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\UserActivity\UserActivityService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserActivity\UserActivityRequest;
use App\Http\Resources\UserActivity\UserActivityResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\UserActivity\UserActivity;

class UserActivityController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected UserActivityService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(UserActivityRequest $request): JsonResponse
    {
        // $this->authorize('store', UserActivity::class);

        $this->service->store($request);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::UserActivity)
            ->setData((object) [])
            ->successResponse();
    }
}
