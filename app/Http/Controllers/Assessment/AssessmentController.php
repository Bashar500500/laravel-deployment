<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\ResponseController;
use App\Services\Assessment\AssessmentService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Assessment\AssessmentRequest;
use App\Http\Resources\Assessment\AssessmentResource;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ModelName;
use App\Models\Assessment\Assessment;
use App\Http\Requests\Assessment\AssessmentSubmitRequest;
use App\Http\Resources\AssessmentSubmit\AssessmentSubmitResource;

class AssessmentController extends Controller
{
    public function __construct(
        ResponseController $controller,
        protected AssessmentService $service,
    ) {
        parent::__construct($controller);
    }

    public function index(AssessmentRequest $request): JsonResponse
    {
        $data = AssessmentResource::collection(
            $this->service->index($request),
        );

        return $this->controller->setFunctionName(FunctionName::Index)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function show(Assessment $assessment): JsonResponse
    {
        $data = AssessmentResource::make(
            $this->service->show($assessment),
        );

        return $this->controller->setFunctionName(FunctionName::Show)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function store(AssessmentRequest $request): JsonResponse
    {
        $data = AssessmentResource::make(
            $this->service->store($request),
        );

        return $this->controller->setFunctionName(FunctionName::Store)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function update(AssessmentRequest $request, Assessment $assessment): JsonResponse
    {
        $data = AssessmentResource::make(
            $this->service->update($request, $assessment),
        );

        return $this->controller->setFunctionName(FunctionName::Update)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function destroy(Assessment $assessment): JsonResponse
    {
        $data = AssessmentResource::make(
            $this->service->destroy($assessment),
        );

        return $this->controller->setFunctionName(FunctionName::Delete)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function submit(AssessmentSubmitRequest $request): JsonResponse
    {
        $data = AssessmentSubmitResource::make(
            $this->service->submit($request),
        );

        return $this->controller->setFunctionName(FunctionName::Submit)
            ->setModelName(ModelName::Assessment)
            ->setData($data)
            ->successResponse();
    }

    public function startTimer(Assessment $assessment): JsonResponse
    {
        $this->service->startTimer($assessment);

        return $this->controller->setFunctionName(FunctionName::StartTimer)
            ->setModelName(ModelName::Assessment)
            ->setData((object) [])
            ->successResponse();
    }

    public function pauseTimer(Assessment $assessment): JsonResponse
    {
        $this->service->pauseTimer($assessment);

        return $this->controller->setFunctionName(FunctionName::PauseTimer)
            ->setModelName(ModelName::Assessment)
            ->setData((object) [])
            ->successResponse();
    }

    public function resumeTimer(Assessment $assessment): JsonResponse
    {
        $this->service->resumeTimer($assessment);

        return $this->controller->setFunctionName(FunctionName::ResumeTimer)
            ->setModelName(ModelName::Assessment)
            ->setData((object) [])
            ->successResponse();
    }

    public function submitTimer(Assessment $assessment): JsonResponse
    {
        $this->service->submitTimer($assessment);

        return $this->controller->setFunctionName(FunctionName::SubmitTimer)
            ->setModelName(ModelName::Assessment)
            ->setData((object) [])
            ->successResponse();
    }

    public function timerStatus(Assessment $assessment): JsonResponse
    {
        $this->service->timerStatus($assessment);

        return $this->controller->setFunctionName(FunctionName::TimerStatus)
            ->setModelName(ModelName::Assessment)
            ->setData((object) [])
            ->successResponse();
    }
}
