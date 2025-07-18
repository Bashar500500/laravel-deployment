<?php

namespace App\Http\Controllers\AssessmentTrueOrFalseQuestion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRequest;
use App\Http\Resources\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestion;

class AssessmentTrueOrFalseQuestionController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentTrueOrFalseQuestionService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentTrueOrFalseQuestionRequest $request): JsonResponse
    {
        $data = AssessmentTrueOrFalseQuestionResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function show(AssessmentTrueOrFalseQuestion $question): JsonResponse
    {
        $data = AssessmentTrueOrFalseQuestionResource::make(
            $this->service->show($question),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssessmentTrueOrFalseQuestionRequest $request): JsonResponse
    {
        $data = AssessmentTrueOrFalseQuestionResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentTrueOrFalseQuestionRequest $request, AssessmentTrueOrFalseQuestion $question): JsonResponse
    {
        $data = AssessmentTrueOrFalseQuestionResource::make(
            $this->service->update($request, $question),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(AssessmentTrueOrFalseQuestion $question): JsonResponse
    {
        $data = AssessmentTrueOrFalseQuestionResource::make(
            $this->service->destroy($question),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData($data)
            ->successResponse();
    }

    public function addAssessmentTrueOrFalseQuestionToQuestionBank(AssessmentTrueOrFalseQuestion $question): JsonResponse
    {
        $this->service->addAssessmentTrueOrFalseQuestionToQuestionBank($question);

        return $this->controller->setFunctionName(FunctionName::AddAssessmentTrueOrFalseQuestionToQuestionBank)
            ->setModelName(ModelName::AssessmentTrueOrFalseQuestion)
            ->setData((object) [])
            ->successResponse();
    }
}
