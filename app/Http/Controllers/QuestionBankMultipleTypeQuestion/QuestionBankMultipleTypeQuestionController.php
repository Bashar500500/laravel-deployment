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
        // $this->authorize('index', [QuestionBankMultipleTypeQuestion::class, $request->validated('question_bank_id')]);

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
        // $this->authorize('show', $question);

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
        // $this->authorize('store', QuestionBankMultipleTypeQuestion::class);

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
        // $this->authorize('update', $question);

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
        // $this->authorize('destroy', $question);

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
        // $this->authorize('addQuestionBankMultipleTypeQuestionToAssessment', $question);

        $this->service->addQuestionBankMultipleTypeQuestionToAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::AddQuestionBankMultipleTypeQuestionToAssessment)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData((object) [])
            ->successResponse();
    }

    public function removeQuestionBankMultipleTypeQuestionFromAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request, QuestionBankMultipleTypeQuestion $question): JsonResponse
    {
        // $this->authorize('removeQuestionBankMultipleTypeQuestionFromAssessment', $question);

        $this->service->removeQuestionBankMultipleTypeQuestionFromAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::RemoveQuestionBankMultipleTypeQuestionFromAssessment)
            ->setModelName(ModelName::QuestionBankMultipleTypeQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
