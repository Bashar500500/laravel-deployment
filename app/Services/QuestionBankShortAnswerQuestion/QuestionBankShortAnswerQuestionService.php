<?php

namespace App\Services\QuestionBankShortAnswerQuestion;

use App\Repositories\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRepositoryInterface;
use App\Http\Requests\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRequest;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\DataTransferObjects\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionDto;
use App\Http\Requests\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest;
use App\DataTransferObjects\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto;

class QuestionBankShortAnswerQuestionService
{
    public function __construct(
        protected QuestionBankShortAnswerQuestionRepositoryInterface $repository,
    ) {}

    public function index(QuestionBankShortAnswerQuestionRequest $request): object
    {
        $dto = QuestionBankShortAnswerQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(QuestionBankShortAnswerQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(QuestionBankShortAnswerQuestionRequest $request): object
    {
        $dto = QuestionBankShortAnswerQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(QuestionBankShortAnswerQuestionRequest $request, QuestionBankShortAnswerQuestion $question): object
    {
        $dto = QuestionBankShortAnswerQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(QuestionBankShortAnswerQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addQuestionBankShortAnswerQuestionToAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest $request, QuestionBankShortAnswerQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->addQuestionBankShortAnswerQuestionToAssessment($dto, $question->id);
    }

    public function removeQuestionBankShortAnswerQuestionFromAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest $request, QuestionBankShortAnswerQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->removeQuestionBankShortAnswerQuestionFromAssessment($dto, $question->id);
    }
}
