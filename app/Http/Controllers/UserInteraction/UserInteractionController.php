<?php

namespace App\Http\Controllers\UserInteraction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\UserInteraction\UserInteractionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserInteraction\UserInteractionRequest;
use App\Http\Resources\UserInteraction\UserInteractionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\UserInteraction\UserInteraction;

class UserInteractionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected UserInteractionService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(UserInteractionRequest $request): JsonResponse
    {
        // $this->authorize('store', UserInteraction::class);

        $this->service->store($request);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::UserInteraction)
            ->setData((object) [])
            ->successResponse();
    }
}
