<?php

namespace App\Http\Controllers\Grade;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Grade\GradeService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Grade\GradeRequest;
use App\Http\Resources\Grade\GradeResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Grade\Grade;

class GradeController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected GradeService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(GradeRequest $request): JsonResponse
    {
        // $this->authorize('index', Grade::class);

        $data = GradeResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Grade)
            ->setData($data)
            ->successResponse();
    }

    public function show(Grade $grade): JsonResponse
    {
        // $this->authorize('show', $grade);

        $data = GradeResource::make(
            $this->service->show($grade),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Grade)
            ->setData($data)
            ->successResponse();
    }

    public function store(GradeRequest $request): JsonResponse
    {
        // $this->authorize('store', Grade::class);

        $data = GradeResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Grade)
            ->setData($data)
            ->successResponse();
    }

    public function update(GradeRequest $request, Grade $grade): JsonResponse
    {
        // $this->authorize('update', $grade);

        $data = GradeResource::make(
            $this->service->update($request, $grade),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Grade)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Grade $grade): JsonResponse
    {
        // $this->authorize('destroy', $grade);

        $data = GradeResource::make(
            $this->service->destroy($grade),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Grade)
            ->setData($data)
            ->successResponse();
    }
}
