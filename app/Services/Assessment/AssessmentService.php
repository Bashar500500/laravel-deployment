<?php

namespace App\Services\Assessment;

use App\Repositories\Assessment\AssessmentRepositoryInterface;
use App\Http\Requests\Assessment\AssessmentRequest;
use App\Models\Assessment\Assessment;
use App\DataTransferObjects\Assessment\AssessmentDto;
use App\Http\Requests\Assessment\AssessmentSubmitRequest;
use App\DataTransferObjects\Assessment\AssessmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssessmentService
{
    public function __construct(
        protected AssessmentRepositoryInterface $repository,
    ) {}

    public function index(AssessmentRequest $request): object
    {
        $dto = AssessmentDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Assessment $assessment): object
    {
        return $this->repository->find($assessment->id);
    }

    public function store(AssessmentRequest $request): object
    {
        $dto = AssessmentDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssessmentRequest $request, Assessment $assessment): object
    {
        $dto = AssessmentDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $assessment->id);
    }

    public function destroy(Assessment $assessment): object
    {
        return $this->repository->delete($assessment->id);
    }

    public function submit(AssessmentSubmitRequest $request): object
    {
        $dto = AssessmentSubmitDto::fromRequest($request);
        $data = $this->prepareAssessmentSubmitData();
        return $this->repository->submit($dto, $data);
    }

    public function startTimer(Assessment $assessment): void
    {
        $this->repository->startTimer($assessment->id);
    }

    public function pauseTimer(Assessment $assessment): void
    {
        $this->repository->pauseTimer($assessment->id);
    }

    public function resumeTimer(Assessment $assessment): void
    {
        $this->repository->resumeTimer($assessment->id);
    }

    public function submitTimer(Assessment $assessment): void
    {
        $this->repository->submitTimer($assessment->id);
    }

    public function timerStatus(Assessment $assessment): void
    {
        $this->repository->timerStatus($assessment->id);
    }

    private function prepareAssessmentSubmitData(): array
    {
        return [
            'student' => Auth::user(),
        ];
    }
}
