<?php

namespace App\Services\AssessmentFillInBlankQuestion;

use App\Repositories\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRepositoryInterface;
use App\Http\Requests\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRequest;
use App\Models\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestion;
use App\DataTransferObjects\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDto;

class AssessmentFillInBlankQuestionService
{
    public function __construct(
        protected AssessmentFillInBlankQuestionRepositoryInterface $repository,
    ) {}

    public function index(AssessmentFillInBlankQuestionRequest $request): object
    {
        $dto = AssessmentFillInBlankQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(AssessmentFillInBlankQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(AssessmentFillInBlankQuestionRequest $request): object
    {
        $dto = AssessmentFillInBlankQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssessmentFillInBlankQuestionRequest $request, AssessmentFillInBlankQuestion $question): object
    {
        $dto = AssessmentFillInBlankQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(AssessmentFillInBlankQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addAssessmentFillInBlankQuestionToQuestionBank(AssessmentFillInBlankQuestion $question): void
    {
        $this->repository->addAssessmentFillInBlankQuestionToQuestionBank($question->id);
    }
}
