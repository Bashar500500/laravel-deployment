<?php

namespace App\Services\AssessmentTrueOrFalseQuestion;

use App\Repositories\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRepositoryInterface;
use App\Http\Requests\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRequest;
use App\Models\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestion;
use App\DataTransferObjects\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDto;

class AssessmentTrueOrFalseQuestionService
{
    public function __construct(
        protected AssessmentTrueOrFalseQuestionRepositoryInterface $repository,
    ) {}

    public function index(AssessmentTrueOrFalseQuestionRequest $request): object
    {
        $dto = AssessmentTrueOrFalseQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(AssessmentTrueOrFalseQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(AssessmentTrueOrFalseQuestionRequest $request): object
    {
        $dto = AssessmentTrueOrFalseQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssessmentTrueOrFalseQuestionRequest $request, AssessmentTrueOrFalseQuestion $question): object
    {
        $dto = AssessmentTrueOrFalseQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(AssessmentTrueOrFalseQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addAssessmentTrueOrFalseQuestionToQuestionBank(AssessmentTrueOrFalseQuestion $question): void
    {
        $this->repository->addAssessmentTrueOrFalseQuestionToQuestionBank($question->id);
    }
}
