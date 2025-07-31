<?php

namespace App\Http\Controllers\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Assignment\AssignmentService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Assignment\AssignmentRequest;
use App\Http\Resources\Assignment\AssignmentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Assignment\Assignment;
use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\Http\Resources\AssignmentSubmit\AssignmentSubmitResource;

class AssignmentController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssignmentService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssignmentRequest $request): JsonResponse
    {
        // $this->authorize('index', [Assignment::class, $request->validated('course_id')]);

        $data = AssignmentResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function show(Assignment $assignment): JsonResponse
    {
        // $this->authorize('show', $assignment);

        $data = AssignmentResource::make(
            $this->service->show($assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssignmentRequest $request): JsonResponse
    {
        // $this->authorize('store', Assignment::class);

        $data = AssignmentResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        // $this->authorize('update', $assignment);

        $data = AssignmentResource::make(
            $this->service->update($request, $assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Assignment $assignment): JsonResponse
    {
        // $this->authorize('destroy', $assignment);

        $data = AssignmentResource::make(
            $this->service->destroy($assignment),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }

    public function submit(AssignmentSubmitRequest $request): JsonResponse
    {
        // $this->authorize('submit', [Assignment::class, $request->validated('assignment_id')]);

        $data = AssignmentSubmitResource::make(
            $this->service->submit($request),
        );

        return $this->controller->setFunctionName(FunctionName::Submit)
            ->setModelName(ModelName::Assignment)
            ->setData($data)
            ->successResponse();
    }
}
