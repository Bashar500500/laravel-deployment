<?php

namespace App\Http\Controllers\Rubric;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Rubric\RubricService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Rubric\RubricRequest;
use App\Http\Resources\Rubric\RubricResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Rubric\Rubric;

class RubricController extends Controller   // Add Policies
{
    public function __construct(
        ResponseController $controller,
        protected RubricService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(RubricRequest $request): JsonResponse
    {
        // $this->authorize('index', Rubric::class);

        $data = RubricResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Rubric)
            ->setData($data)
            ->successResponse();
    }

    public function show(Rubric $rubric): JsonResponse
    {
        // $this->authorize('show', $rubric);

        $data = RubricResource::make(
            $this->service->show($rubric),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Rubric)
            ->setData($data)
            ->successResponse();
    }

    public function store(RubricRequest $request): JsonResponse
    {
        // $this->authorize('store', Rubric::class);

        $data = RubricResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Rubric)
            ->setData($data)
            ->successResponse();
    }

    public function update(RubricRequest $request, Rubric $rubric): JsonResponse
    {
        // $this->authorize('update', $rubric);

        $data = RubricResource::make(
            $this->service->update($request, $rubric),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Rubric)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Rubric $rubric): JsonResponse
    {
        // $this->authorize('destroy', $rubric);

        $data = RubricResource::make(
            $this->service->destroy($rubric),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Rubric)
            ->setData($data)
            ->successResponse();
    }
}
