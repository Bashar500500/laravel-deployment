<?php

namespace App\Http\Controllers\TeachingHour;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\TeachingHour\TeachingHourService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TeachingHour\TeachingHourRequest;
use App\Http\Resources\TeachingHour\TeachingHourResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\TeachingHour\TeachingHour;

class TeachingHourController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected TeachingHourService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(TeachingHourRequest $request): JsonResponse
    {
        // $this->authorize('index', TeachingHour::class);

        $data = TeachingHourResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::TeachingHour)
            ->setData($data)
            ->successResponse();
    }

    public function show(TeachingHour $teachingHour): JsonResponse
    {
        // $this->authorize('show', $teachingHour);

        $data = TeachingHourResource::make(
            $this->service->show($teachingHour),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::TeachingHour)
            ->setData($data)
            ->successResponse();
    }

    public function store(TeachingHourRequest $request): JsonResponse
    {
        // $this->authorize('store', TeachingHour::class);

        $data = TeachingHourResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::TeachingHour)
            ->setData($data)
            ->successResponse();
    }

    public function update(TeachingHourRequest $request, TeachingHour $teachingHour): JsonResponse
    {
        // $this->authorize('update', $teachingHour);

        $data = TeachingHourResource::make(
            $this->service->update($request, $teachingHour),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::TeachingHour)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(TeachingHour $teachingHour): JsonResponse
    {
        // $this->authorize('destroy', $teachingHour);

        $data = TeachingHourResource::make(
            $this->service->destroy($teachingHour),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::TeachingHour)
            ->setData($data)
            ->successResponse();
    }
}
