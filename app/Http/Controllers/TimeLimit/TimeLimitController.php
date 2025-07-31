<?php

namespace App\Http\Controllers\TimeLimit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\TimeLimit\TimeLimitService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TimeLimit\TimeLimitRequest;
use App\Http\Resources\TimeLimit\TimeLimitResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\TimeLimit\TimeLimit;

class TimeLimitController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected TimeLimitService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(TimeLimitRequest $request): JsonResponse
    {
        // $this->authorize('index', TimeLimit::class);

        $data = TimeLimitResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::TimeLimit)
            ->setData($data)
            ->successResponse();
    }

    public function show(TimeLimit $timeLimit): JsonResponse
    {
        // $this->authorize('show', $timeLimit);

        $data = TimeLimitResource::make(
            $this->service->show($timeLimit),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::TimeLimit)
            ->setData($data)
            ->successResponse();
    }

    public function store(TimeLimitRequest $request): JsonResponse
    {
        // $this->authorize('store', TimeLimit::class);

        $data = TimeLimitResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::TimeLimit)
            ->setData($data)
            ->successResponse();
    }

    public function update(TimeLimitRequest $request, TimeLimit $timeLimit): JsonResponse
    {
        // $this->authorize('update', $timeLimit);

        $data = TimeLimitResource::make(
            $this->service->update($request, $timeLimit),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::TimeLimit)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(TimeLimit $timeLimit): JsonResponse
    {
        // $this->authorize('destroy', $timeLimit);

        $data = TimeLimitResource::make(
            $this->service->destroy($timeLimit),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::TimeLimit)
            ->setData($data)
            ->successResponse();
    }
}
