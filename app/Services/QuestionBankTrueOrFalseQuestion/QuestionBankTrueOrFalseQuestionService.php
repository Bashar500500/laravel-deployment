<?php

namespace App\Services\QuestionBankTrueOrFalseQuestion;

use App\Repositories\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRepositoryInterface;
use App\Http\Requests\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRequest;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionDto;
use App\Http\Requests\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest;
use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto;

class QuestionBankTrueOrFalseQuestionService
{
    public function __construct(
        protected QuestionBankTrueOrFalseQuestionRepositoryInterface $repository,
    ) {}

    public function index(QuestionBankTrueOrFalseQuestionRequest $request): object
    {
        $dto = QuestionBankTrueOrFalseQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(QuestionBankTrueOrFalseQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(QuestionBankTrueOrFalseQuestionRequest $request): object
    {
        $dto = QuestionBankTrueOrFalseQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(QuestionBankTrueOrFalseQuestionRequest $request, QuestionBankTrueOrFalseQuestion $question): object
    {
        $dto = QuestionBankTrueOrFalseQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(QuestionBankTrueOrFalseQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addQuestionBankTrueOrFalseQuestionToAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest $request, QuestionBankTrueOrFalseQuestion $question): void
    {
        // dd($question->id);
        $dto = AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->addQuestionBankTrueOrFalseQuestionToAssessment($dto, $question->id);
    }

    public function removeQuestionBankTrueOrFalseQuestionFromAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest $request, QuestionBankTrueOrFalseQuestion $question): void
    {
        $dto = AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto::fromRequest($request);
        $this->repository->removeQuestionBankTrueOrFalseQuestionFromAssessment($dto, $question->id);
    }
}
