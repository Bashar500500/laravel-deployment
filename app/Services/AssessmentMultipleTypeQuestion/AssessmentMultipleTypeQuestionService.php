<?php

namespace App\Services\AssessmentMultipleTypeQuestion;

use App\Repositories\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRepositoryInterface;
use App\Http\Requests\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRequest;
use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;
use App\DataTransferObjects\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionDto;

class AssessmentMultipleTypeQuestionService
{
    public function __construct(
        protected AssessmentMultipleTypeQuestionRepositoryInterface $repository,
    ) {}

    public function index(AssessmentMultipleTypeQuestionRequest $request): object
    {
        $dto = AssessmentMultipleTypeQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(AssessmentMultipleTypeQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(AssessmentMultipleTypeQuestionRequest $request): object
    {
        $dto = AssessmentMultipleTypeQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssessmentMultipleTypeQuestionRequest $request, AssessmentMultipleTypeQuestion $question): object
    {
        $dto = AssessmentMultipleTypeQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(AssessmentMultipleTypeQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addAssessmentMultipleTypeQuestionToQuestionBank(AssessmentMultipleTypeQuestion $question): void
    {
        $this->repository->addAssessmentMultipleTypeQuestionToQuestionBank($question->id);
    }
}
