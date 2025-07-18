<?php

namespace App\Http\Controllers\QuestionBankShortAnswerQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRequest;
use App\Http\Resources\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\Http\Requests\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest;

class QuestionBankShortAnswerQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionBankShortAnswerQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionBankShortAnswerQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankShortAnswerQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(QuestionBankShortAnswerQuestion $question): JsonResponse
    {
        $data = QuestionBankShortAnswerQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionBankShortAnswerQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankShortAnswerQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(QuestionBankShortAnswerQuestionRequest $request, QuestionBankShortAnswerQuestion $question): JsonResponse
    {
        $data = QuestionBankShortAnswerQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(QuestionBankShortAnswerQuestion $question): JsonResponse
    {
        $data = QuestionBankShortAnswerQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addQuestionBankShortAnswerQuestionToAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest $request, QuestionBankShortAnswerQuestion $question): JsonResponse
    {
        $this->service->addQuestionBankShortAnswerQuestionToAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::AddQuestionBankShortAnswerQuestionToAssessment)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData((object) [])
            ->successResponse();
    }

    public function removeQuestionBankShortAnswerQuestionFromAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest $request, QuestionBankShortAnswerQuestion $question): JsonResponse
    {
        $this->service->removeQuestionBankShortAnswerQuestionFromAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::RemoveQuestionBankShortAnswerQuestionFromAssessment)
            ->setModelName(ModelName::QuestionBankShortAnswerQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
