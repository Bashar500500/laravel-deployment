<?php

namespace App\Http\Controllers\QuestionBankFillInBlankQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRequest;
use App\Http\Resources\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;
use App\Http\Requests\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest;

class QuestionBankFillInBlankQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionBankFillInBlankQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionBankFillInBlankQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankFillInBlankQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(QuestionBankFillInBlankQuestion $question): JsonResponse
    {
        $data = QuestionBankFillInBlankQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionBankFillInBlankQuestionRequest $request): JsonResponse
    {
        $data = QuestionBankFillInBlankQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(QuestionBankFillInBlankQuestionRequest $request, QuestionBankFillInBlankQuestion $question): JsonResponse
    {
        $data = QuestionBankFillInBlankQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(QuestionBankFillInBlankQuestion $question): JsonResponse
    {
        $data = QuestionBankFillInBlankQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addQuestionBankFillInBlankQuestionToAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest $request, QuestionBankFillInBlankQuestion $question): JsonResponse
    {
        $this->service->addQuestionBankFillInBlankQuestionToAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::AddQuestionBankFillInBlankQuestionToAssessment)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData((object) [])
            ->successResponse();
    }

    public function removeQuestionBankFillInBlankQuestionFromAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest $request, QuestionBankFillInBlankQuestion $question): JsonResponse
    {
        $this->service->removeQuestionBankFillInBlankQuestionFromAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::RemoveQuestionBankFillInBlankQuestionFromAssessment)
            ->setModelName(ModelName::QuestionBankFillInBlankQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
