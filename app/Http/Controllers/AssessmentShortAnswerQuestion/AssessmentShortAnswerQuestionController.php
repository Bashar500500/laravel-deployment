<?php

namespace App\Http\Controllers\AssessmentShortAnswerQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRequest;
use App\Http\Resources\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestion;

class AssessmentShortAnswerQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentShortAnswerQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentShortAnswerQuestionRequest $request): JsonResponse
    {
        // $this->authorize('index', [AssessmentShortAnswerQuestion::class, $request->validated('assessment_id')]);

        $data = AssessmentShortAnswerQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssessmentShortAnswerQuestion $question): JsonResponse
    {
        // $this->authorize('show', $question);

        $data = AssessmentShortAnswerQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssessmentShortAnswerQuestionRequest $request): JsonResponse
    {
        // $this->authorize('store', AssessmentShortAnswerQuestion::class);

        $data = AssessmentShortAnswerQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentShortAnswerQuestionRequest $request, AssessmentShortAnswerQuestion $question): JsonResponse
    {
        // $this->authorize('update', $question);

        $data = AssessmentShortAnswerQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssessmentShortAnswerQuestion $question): JsonResponse
    {
        // $this->authorize('destroy', $question);

        $data = AssessmentShortAnswerQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addAssessmentShortAnswerQuestionToQuestionBank(AssessmentShortAnswerQuestion $question): JsonResponse
    {
        // $this->authorize('addAssessmentShortAnswerQuestionToQuestionBank', $question);

        $this->service->addAssessmentShortAnswerQuestionToQuestionBank($question);

        return $this->controller->setFunctionName(FunctionName::AddAssessmentShortAnswerQuestionToQuestionBank)
            ->setModelName(ModelName::AssessmentShortAnswerQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
