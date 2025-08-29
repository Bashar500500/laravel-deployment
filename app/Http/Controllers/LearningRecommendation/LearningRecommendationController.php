<?php

namespace App\Http\Controllers\LearningRecommendation;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\LearningRecommendation\LearningRecommendationService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LearningRecommendation\LearningRecommendationRequest;
use App\Http\Resources\LearningRecommendation\LearningRecommendationResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\LearningRecommendation\LearningRecommendation;

class LearningRecommendationController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected LearningRecommendationService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(LearningRecommendationRequest $request): JsonResponse
    {
        // $this->authorize('index', LearningRecommendation::class);

        $data = LearningRecommendationResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::LearningRecommendation)
            ->setData($data)
            ->successResponse();
    }

    public function show(LearningRecommendation $learningRecommendation): JsonResponse
    {
        // $this->authorize('show', $learningRecommendation);

        $data = LearningRecommendationResource::make(
            $this->service->show($learningRecommendation),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::LearningRecommendation)
            ->setData($data)
            ->successResponse();
    }

    public function store(LearningRecommendationRequest $request): JsonResponse
    {
        // $this->authorize('store', LearningRecommendation::class);

        $data = LearningRecommendationResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::LearningRecommendation)
            ->setData($data)
            ->successResponse();
    }

    public function update(LearningRecommendationRequest $request, LearningRecommendation $learningRecommendation): JsonResponse
    {
        // $this->authorize('update', $learningRecommendation);

        $data = LearningRecommendationResource::make(
            $this->service->update($request, $learningRecommendation),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::LearningRecommendation)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(LearningRecommendation $learningRecommendation): JsonResponse
    {
        // $this->authorize('destroy', $learningRecommendation);

        $data = LearningRecommendationResource::make(
            $this->service->destroy($learningRecommendation),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::LearningRecommendation)
            ->setData($data)
            ->successResponse();
    }
}
