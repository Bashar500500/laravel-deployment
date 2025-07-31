<?php

namespace App\Http\Controllers\AssessmentMultipleTypeQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRequest;
use App\Http\Resources\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;

class AssessmentMultipleTypeQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentMultipleTypeQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentMultipleTypeQuestionRequest $request): JsonResponse
    {
        // $this->authorize('index', [AssessmentMultipleTypeQuestion::class, $request->validated('assessment_id')]);

        $data = AssessmentMultipleTypeQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssessmentMultipleTypeQuestion $question): JsonResponse
    {
        // $this->authorize('show', $question);

        $data = AssessmentMultipleTypeQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssessmentMultipleTypeQuestionRequest $request): JsonResponse
    {
        // $this->authorize('store', AssessmentMultipleTypeQuestion::class);

        $data = AssessmentMultipleTypeQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentMultipleTypeQuestionRequest $request, AssessmentMultipleTypeQuestion $question): JsonResponse
    {
        // $this->authorize('update', $question);

        $data = AssessmentMultipleTypeQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssessmentMultipleTypeQuestion $question): JsonResponse
    {
        // $this->authorize('destroy', $question);

        $data = AssessmentMultipleTypeQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addAssessmentMultipleTypeQuestionToQuestionBank(AssessmentMultipleTypeQuestion $question): JsonResponse
    {
        // $this->authorize('addAssessmentMultipleTypeQuestionToQuestionBank', $question);

        $this->service->addAssessmentMultipleTypeQuestionToQuestionBank($question);

        return $this->controller->setFunctionName(FunctionName::AddAssessmentMultipleTypeQuestionToQuestionBank)
            ->setModelName(ModelName::AssessmentMultipleTypeQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
