<?php

namespace App\Services\Assessment;

use App\Factories\Assessment\AssessmentRepositoryFactory;
use App\Http\Requests\Assessment\AssessmentRequest;
use App\Models\Assessment\Assessment;
use App\DataTransferObjects\Assessment\AssessmentDto;
use App\Http\Requests\Assessment\AssessmentSubmitRequest;
use App\DataTransferObjects\Assessment\AssessmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssessmentService
{
    public function __construct(
        protected AssessmentRepositoryFactory $factory,
    ) {}

    public function index(AssessmentRequest $request): object
    {
        $dto = AssessmentDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto);
    }

    public function show(Assessment $assessment): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($assessment->id);
    }

    public function store(AssessmentRequest $request): object
    {
        $dto = AssessmentDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto);
    }

    public function update(AssessmentRequest $request, Assessment $assessment): object
    {
        $dto = AssessmentDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $assessment->id);
    }

    public function destroy(Assessment $assessment): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($assessment->id);
    }

    public function submit(AssessmentSubmitRequest $request): object
    {
        $dto = AssessmentSubmitDto::fromRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareAssessmentSubmitData();
        $repository = $this->factory->make($role[0]);
        return $repository->submit($dto, $data);
    }

    public function startTimer(Assessment $assessment): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->startTimer($assessment->id);
    }

    public function pauseTimer(Assessment $assessment): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->pauseTimer($assessment->id);
    }

    public function resumeTimer(Assessment $assessment): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->resumeTimer($assessment->id);
    }

    public function submitTimer(Assessment $assessment): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->submitTimer($assessment->id);
    }

    public function timerStatus(Assessment $assessment): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->timerStatus($assessment->id);
    }

    private function prepareAssessmentSubmitData(): array
    {
        return [
            'student' => Auth::user(),
        ];
    }
}
