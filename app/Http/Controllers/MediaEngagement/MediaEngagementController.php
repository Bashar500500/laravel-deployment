<?php

namespace App\Http\Controllers\MediaEngagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\MediaEngagement\MediaEngagementService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MediaEngagement\MediaEngagementRequest;
use App\Http\Resources\MediaEngagement\MediaEngagementResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\MediaEngagement\MediaEngagement;

class MediaEngagementController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected MediaEngagementService $service,
    ) {
        parent::__construct($controller);
    }

    public function store(MediaEngagementRequest $request): JsonResponse
    {
        // $this->authorize('store', MediaEngagement::class);

        $this->service->store($request);

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::MediaEngagement)
            ->setData((object) [])
            ->successResponse();
    }
}
