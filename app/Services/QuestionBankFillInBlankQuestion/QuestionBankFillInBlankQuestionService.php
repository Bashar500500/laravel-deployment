<?php

namespace App\Services\QuestionBankFillInBlankQuestion;

use App\Repositories\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRepositoryInterface;
use App\Http\Requests\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRequest;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;
use App\DataTransferObjects\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionDto;
use App\Http\Requests\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest;
use App\DataTransferObjects\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto;

class QuestionBankFillInBlankQuestionService
{
    public function __construct(
        protected QuestionBankFillInBlankQuestionRepositoryInterface $repository,
    ) {}

    public function index(QuestionBankFillInBlankQuestionRequest $request): object
    {
        $dto = QuestionBankFillInBlankQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(QuestionBankFillInBlankQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(QuestionBankFillInBlankQuestionRequest $request): object
    {
        $dto = QuestionBankFillInBlankQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(QuestionBankFillInBlankQuestionRequest $request, QuestionBankFillInBlankQuestion $question): object
    {
        $dto = QuestionBankFillInBlankQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(QuestionBankFillInBlankQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addQuestionBankFillInBlankQuestionToAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest $request, QuestionBankFillInBlankQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->addQuestionBankFillInBlankQuestionToAssessment($dto, $question->id);
    }

    public function removeQuestionBankFillInBlankQuestionFromAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest $request, QuestionBankFillInBlankQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->removeQuestionBankFillInBlankQuestionFromAssessment($dto, $question->id);
    }
}
