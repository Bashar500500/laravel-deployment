<?php

namespace App\Http\Controllers\QuestionBankMultipleTypeQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRequest;
use App\Http\Resources\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\Http\Requests\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest;

class QuestionBankMultipleTypeQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionBankMultipleTypeQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionBankMultipleTypeQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankMultipleTypeQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        $data = QuestionBankMultipleTypeQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionBankMultipleTypeQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankMultipleTypeQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(QuestionBankMultipleTypeQuestionRequest $request, QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        $data = QuestionBankMultipleTypeQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        $data = QuestionBankMultipleTypeQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addQuestionBankMultipleTypeQuestionToAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request, QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        $this->service->addQuestionBankMultipleTypeQuestionToAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::AddQuestionBankMultipleTypeQuestionToAssessment)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData((object) [])
            ->successResponse();
    }

    public function removeQuestionBankMultipleTypeQuestionFromAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request, QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        $this->service->removeQuestionBankMultipleTypeQuestionFromAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::RemoveQuestionBankMultipleTypeQuestionFromAssessment)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
