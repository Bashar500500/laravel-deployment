<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Holiday\HolidayService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Holiday\HolidayRequest;
use App\Http\Resources\Holiday\HolidayResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Holiday\Holiday;

class HolidayController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected HolidayService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(HolidayRequest $request): JsonResponse
    {
        // $this->authorize('index', Holiday::class);

        $data = HolidayResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Holiday)
            ->setData($data)
            ->successResponse();
    }

    public function show(Holiday $holiday): JsonResponse
    {
        // $this->authorize('show', $holiday);

        $data = HolidayResource::make(
            $this->service->show($holiday),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Holiday)
            ->setData($data)
            ->successResponse();
    }

    public function store(HolidayRequest $request): JsonResponse
    {
        // $this->authorize('store', Holiday::class);

        $data = HolidayResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Holiday)
            ->setData($data)
            ->successResponse();
    }

    public function update(HolidayRequest $request, Holiday $holiday): JsonResponse
    {
        // $this->authorize('update', $holiday);

        $data = HolidayResource::make(
            $this->service->update($request, $holiday),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Holiday)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Holiday $holiday): JsonResponse
    {
        // $this->authorize('destroy', $holiday);

        $data = HolidayResource::make(
            $this->service->destroy($holiday),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Holiday)
            ->setData($data)
            ->successResponse();
    }
}
