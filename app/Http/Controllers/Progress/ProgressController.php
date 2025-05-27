<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Progress\ProgressService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Progress\ProgressRequest;
use App\Http\Resources\Progress\ProgressResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Progress\Progress;

class ProgressController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ProgressService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ProgressRequest $request): JsonResponse
    {
        $data = ProgressResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Progress)
            ->setData($data)
            ->successResponse();
    }

    public function show(Progress $progress): JsonResponse
    {
        $data = ProgressResource::make(
            $this->service->show($progress),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Progress)
            ->setData($data)
            ->successResponse();
    }

    public function store(ProgressRequest $request): JsonResponse
    {
        $data = ProgressResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Progress)
            ->setData($data)
            ->successResponse();
    }

    public function update(ProgressRequest $request, Progress $progress): JsonResponse
    {
        $data = ProgressResource::make(
            $this->service->update($request, $progress),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Progress)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Progress $progress): JsonResponse
    {
        $data = ProgressResource::make(
            $this->service->destroy($progress),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Progress)
            ->setData($data)
            ->successResponse();
    }
}
