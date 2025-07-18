<?php

namespace App\Http\Controllers\AssessmentFillInBlankQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRequest;
use App\Http\Resources\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestion;

class AssessmentFillInBlankQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentFillInBlankQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentFillInBlankQuestionRequest $request): JsonResponse
    {
        $data = AssessmentFillInBlankQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssessmentFillInBlankQuestion $question): JsonResponse
    {
        $data = AssessmentFillInBlankQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssessmentFillInBlankQuestionRequest $request): JsonResponse
    {
        $data = AssessmentFillInBlankQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentFillInBlankQuestionRequest $request, AssessmentFillInBlankQuestion $question): JsonResponse
    {
        $data = AssessmentFillInBlankQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssessmentFillInBlankQuestion $question): JsonResponse
    {
        $data = AssessmentFillInBlankQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addAssessmentFillInBlankQuestionToQuestionBank(AssessmentFillInBlankQuestion $question): JsonResponse
    {
        $this->service->addAssessmentFillInBlankQuestionToQuestionBank($question);

        return $this->controller->setFunctionName(FunctionName::AddAssessmentFillInBlankQuestionToQuestionBank)
            ->setModelName(ModelName::AssessmentFillInBlankQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
