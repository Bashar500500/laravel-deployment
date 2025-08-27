<?php

namespace App\Http\Controllers\EnrollmentOption;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\EnrollmentOption\EnrollmentOptionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\EnrollmentOption\EnrollmentOptionRequest;
use App\Http\Resources\EnrollmentOption\EnrollmentOptionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\EnrollmentOption\EnrollmentOption;

class EnrollmentOptionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected EnrollmentOptionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(EnrollmentOptionRequest $request): JsonResponse
    {
        // $this->authorize('index', EnrollmentOption::class);

        $data = EnrollmentOptionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::EnrollmentOption)
            ->setData($data)
            ->successResponse();
    }

    public function show(EnrollmentOption $enrollmentOption): JsonResponse
    {
        // $this->authorize('show', $enrollmentOption);

        $data = EnrollmentOptionResource::make(
            $this->service->show($enrollmentOption),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::EnrollmentOption)
            ->setData($data)
            ->successResponse();
    }

    public function store(EnrollmentOptionRequest $request): JsonResponse
    {
        // $this->authorize('store', EnrollmentOption::class);

        $data = EnrollmentOptionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::EnrollmentOption)
            ->setData($data)
            ->successResponse();
    }

    public function update(EnrollmentOptionRequest $request, EnrollmentOption $enrollmentOption): JsonResponse
    {
        // $this->authorize('update', $enrollmentOption);

        $data = EnrollmentOptionResource::make(
            $this->service->update($request, $enrollmentOption),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::EnrollmentOption)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(EnrollmentOption $enrollmentOption): JsonResponse
    {
        // $this->authorize('destroy', $enrollmentOption);

        $data = EnrollmentOptionResource::make(
            $this->service->destroy($enrollmentOption),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::EnrollmentOption)
            ->setData($data)
            ->successResponse();
    }
}
