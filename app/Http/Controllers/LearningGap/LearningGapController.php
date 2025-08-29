<?php

namespace App\Http\Controllers\LearningGap;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\LearningGap\LearningGapService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LearningGap\LearningGapRequest;
use App\Http\Resources\LearningGap\LearningGapResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\LearningGap\LearningGap;

class LearningGapController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected LearningGapService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(LearningGapRequest $request): JsonResponse
    {
        // $this->authorize('index', LearningGap::class);

        $data = LearningGapResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::LearningGap)
            ->setData($data)
            ->successResponse();
    }

    public function show(LearningGap $learningGap): JsonResponse
    {
        // $this->authorize('show', $learningGap);

        $data = LearningGapResource::make(
            $this->service->show($learningGap),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::LearningGap)
            ->setData($data)
            ->successResponse();
    }

    public function store(LearningGapRequest $request): JsonResponse
    {
        // $this->authorize('store', LearningGap::class);

        $data = LearningGapResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::LearningGap)
            ->setData($data)
            ->successResponse();
    }

    public function update(LearningGapRequest $request, LearningGap $learningGap): JsonResponse
    {
        // $this->authorize('update', $learningGap);

        $data = LearningGapResource::make(
            $this->service->update($request, $learningGap),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::LearningGap)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(LearningGap $learningGap): JsonResponse
    {
        // $this->authorize('destroy', $learningGap);

        $data = LearningGapResource::make(
            $this->service->destroy($learningGap),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::LearningGap)
            ->setData($data)
            ->successResponse();
    }
}
