<?php

namespace App\Services\QuestionBankMultipleTypeQuestion;

use App\Repositories\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRepositoryInterface;
use App\Http\Requests\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRequest;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionDto;
use App\Http\Requests\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest;
use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto;

class QuestionBankMultipleTypeQuestionService
{
    public function __construct(
        protected QuestionBankMultipleTypeQuestionRepositoryInterface $repository,
    ) {}

    public function index(QuestionBankMultipleTypeQuestionRequest $request): object
    {
        $dto = QuestionBankMultipleTypeQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(QuestionBankMultipleTypeQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(QuestionBankMultipleTypeQuestionRequest $request): object
    {
        $dto = QuestionBankMultipleTypeQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(QuestionBankMultipleTypeQuestionRequest $request, QuestionBankMultipleTypeQuestion $question): object
    {
        $dto = QuestionBankMultipleTypeQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(QuestionBankMultipleTypeQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addQuestionBankMultipleTypeQuestionToAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request, QuestionBankMultipleTypeQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->addQuestionBankMultipleTypeQuestionToAssessment($dto, $question->id);
    }

    public function removeQuestionBankMultipleTypeQuestionFromAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request, QuestionBankMultipleTypeQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->removeQuestionBankMultipleTypeQuestionFromAssessment($dto, $question->id);
    }
}
