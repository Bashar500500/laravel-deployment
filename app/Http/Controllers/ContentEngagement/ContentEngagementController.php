<?php

namespace App\Http\Controllers\ContentEngagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\ContentEngagement\ContentEngagementService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ContentEngagement\ContentEngagementRequest;
use App\Http\Resources\ContentEngagement\ContentEngagementResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\ContentEngagement\ContentEngagement;

class ContentEngagementController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ContentEngagementService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(ContentEngagementRequest $request): JsonResponse
    {
        // $this->authorize('store', ContentEngagement::class);

        $this->service->store($request);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::ContentEngagement)
            ->setData((object) [])
            ->successResponse();
    }
}
