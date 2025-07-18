<?php

namespace App\Services\AssessmentSubmit;

use App\Factories\AssessmentSubmit\AssessmentSubmitRepositoryFactory;
use App\Http\Requests\AssessmentSubmit\AssessmentSubmitRequest;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\DataTransferObjects\AssessmentSubmit\AssessmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssessmentSubmitService
{
    public function __construct(
        protected AssessmentSubmitRepositoryFactory $factory,
    ) {}

    public function index(AssessmentSubmitRequest $request): object
    {
        $dto = AssessmentSubmitDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexData();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto, $data);
    }

    public function show(AssessmentSubmit $assessmentSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($assessmentSubmit->id);
    }

    public function update(AssessmentSubmitRequest $request, AssessmentSubmit $assessmentSubmit): object
    {
        $dto = AssessmentSubmitDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $assessmentSubmit->id);
    }

    public function destroy(AssessmentSubmit $assessmentSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($assessmentSubmit->id);
    }

    private function prepareIndexData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
