<?php

namespace App\Http\Controllers\AssessmentSubmit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssessmentSubmit\AssessmentSubmitService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssessmentSubmit\AssessmentSubmitRequest;
use App\Http\Resources\AssessmentSubmit\AssessmentSubmitResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssessmentSubmit\AssessmentSubmit;

class AssessmentSubmitController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentSubmitService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentSubmitRequest $request): JsonResponse
    {
        // $this->authorize('index', [AssessmentSubmit::class, $request->validated('assessment_id')]);

        $data = AssessmentSubmitResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssessmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssessmentSubmit $assessmentSubmit): JsonResponse
    {
        // $this->authorize('show', $assessmentSubmit);

        $data = AssessmentSubmitResource::make(
            $this->service->show($assessmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssessmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentSubmitRequest $request, AssessmentSubmit $assessmentSubmit): JsonResponse
    {
        // $this->authorize('update', $assessmentSubmit);

        $data = AssessmentSubmitResource::make(
            $this->service->update($request, $assessmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssessmentSubmit)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssessmentSubmit $assessmentSubmit): JsonResponse
    {
        // $this->authorize('destroy', $assessmentSubmit);

        $data = AssessmentSubmitResource::make(
            $this->service->destroy($assessmentSubmit),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssessmentSubmit)
            ->setData($data)
            ->successResponse();
    }
}
