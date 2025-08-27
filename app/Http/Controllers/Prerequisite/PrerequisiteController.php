<?php

namespace App\Http\Controllers\Prerequisite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Prerequisite\PrerequisiteService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Prerequisite\PrerequisiteRequest;
use App\Http\Resources\Prerequisite\PrerequisiteResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Prerequisite\Prerequisite;

class PrerequisiteController extends Controller   // Add policies
{
    public function __construct(
        ResponseController $controller,
        protected PrerequisiteService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(PrerequisiteRequest $request): JsonResponse
    {
        // $this->authorize('index', Prerequisite::class);

        $data = PrerequisiteResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Prerequisite)
            ->setData($data)
            ->successResponse();
    }

    public function show(Prerequisite $prerequisite): JsonResponse
    {
        // $this->authorize('show', $prerequisite);

        $data = PrerequisiteResource::make(
            $this->service->show($prerequisite),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Prerequisite)
            ->setData($data)
            ->successResponse();
    }

    public function store(PrerequisiteRequest $request): JsonResponse
    {
        // $this->authorize('store', Prerequisite::class);

        $data = PrerequisiteResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Prerequisite)
            ->setData($data)
            ->successResponse();
    }

    public function update(PrerequisiteRequest $request, Prerequisite $prerequisite): JsonResponse
    {
        // $this->authorize('update', $prerequisite);

        $data = PrerequisiteResource::make(
            $this->service->update($request, $prerequisite),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Prerequisite)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Prerequisite $prerequisite): JsonResponse
    {
        // $this->authorize('destroy', $prerequisite);

        $data = PrerequisiteResource::make(
            $this->service->destroy($prerequisite),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Prerequisite)
            ->setData($data)
            ->successResponse();
    }
}
