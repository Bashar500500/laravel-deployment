<?php

namespace App\Http\Controllers\QuestionBankTrueOrFalseQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRequest;
use App\Http\Resources\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\Http\Requests\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest;

class QuestionBankTrueOrFalseQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected QuestionBankTrueOrFalseQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(QuestionBankTrueOrFalseQuestionRequest $request): JsonResponse
    {
        // $this->authorize('index', [QuestionBankTrueOrFalseQuestion::class, $request->validated('question_bank_id')]);

        $data = QuestionBankTrueOrFalseQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(QuestionBankTrueOrFalseQuestion $question): JsonResponse
    {
        // $this->authorize('show', $question);

        $data = QuestionBankTrueOrFalseQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(QuestionBankTrueOrFalseQuestionRequest $request): JsonResponse
    {
        // $this->authorize('store', QuestionBankTrueOrFalseQuestion::class);

        $data = QuestionBankTrueOrFalseQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(QuestionBankTrueOrFalseQuestionRequest $request, QuestionBankTrueOrFalseQuestion $question): JsonResponse
    {
        // $this->authorize('update', $question);

        $data = QuestionBankTrueOrFalseQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(QuestionBankTrueOrFalseQuestion $question): JsonResponse
    {
        // $this->authorize('destroy', $question);

        $data = QuestionBankTrueOrFalseQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addQuestionBankTrueOrFalseQuestionToAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest $request, QuestionBankTrueOrFalseQuestion $question): JsonResponse
    {
        // $this->authorize('addQuestionBankTrueOrFalseQuestionToAssessment', $question);

        $this->service->addQuestionBankTrueOrFalseQuestionToAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::AddQuestionBankTrueOrFalseQuestionToAssessment)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData((object) [])
            ->successResponse();
    }

    public function removeQuestionBankTrueOrFalseQuestionFromAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest $request, QuestionBankTrueOrFalseQuestion $question): JsonResponse
    {
        // $this->authorize('removeQuestionBankTrueOrFalseQuestionFromAssessment', $question);

        $this->service->removeQuestionBankTrueOrFalseQuestionFromAssessment($request, $question);

        return $this->controller->setFunctionName(FunctionName::RemoveQuestionBankTrueOrFalseQuestionFromAssessment)
            ->setModelName(ModelName::QuestionBankTrueOrFalseQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
