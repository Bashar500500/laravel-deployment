<?php

namespace App\Http\Controllers\ScheduleTiming;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\ScheduleTiming\ScheduleTimingService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ScheduleTiming\ScheduleTimingRequest;
use App\Http\Resources\ScheduleTiming\ScheduleTimingResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\ScheduleTiming\ScheduleTiming;

class ScheduleTimingController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected ScheduleTimingService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(ScheduleTimingRequest $request): JsonResponse
    {
        // $this->authorize('index', ScheduleTiming::class);

        $data = ScheduleTimingResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::ScheduleTiming)
            ->setData($data)
            ->successResponse();
    }

    public function show(ScheduleTiming $scheduleTiming): JsonResponse
    {
        // $this->authorize('show', $scheduleTiming);

        $data = ScheduleTimingResource::make(
            $this->service->show($scheduleTiming),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::ScheduleTiming)
            ->setData($data)
            ->successResponse();
    }

    public function store(ScheduleTimingRequest $request): JsonResponse
    {
        // $this->authorize('store', ScheduleTiming::class);

        $data = ScheduleTimingResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::ScheduleTiming)
            ->setData($data)
            ->successResponse();
    }

    public function update(ScheduleTimingRequest $request, ScheduleTiming $scheduleTiming): JsonResponse
    {
        // $this->authorize('update', $scheduleTiming);

        $data = ScheduleTimingResource::make(
            $this->service->update($request, $scheduleTiming),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::ScheduleTiming)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(ScheduleTiming $scheduleTiming): JsonResponse
    {
        // $this->authorize('destroy', $scheduleTiming);

        $data = ScheduleTimingResource::make(
            $this->service->destroy($scheduleTiming),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::ScheduleTiming)
            ->setData($data)
            ->successResponse();
    }
}
