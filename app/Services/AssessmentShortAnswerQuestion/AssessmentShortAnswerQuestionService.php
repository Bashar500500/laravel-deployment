<?php

namespace App\Services\AssessmentShortAnswerQuestion;

use App\Repositories\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRepositoryInterface;
use App\Http\Requests\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRequest;
use App\Models\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestion;
use App\DataTransferObjects\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionDto;

class AssessmentShortAnswerQuestionService
{
    public function __construct(
        protected AssessmentShortAnswerQuestionRepositoryInterface $repository,
    ) {}

    public function index(AssessmentShortAnswerQuestionRequest $request): object
    {
        $dto = AssessmentShortAnswerQuestionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(AssessmentShortAnswerQuestion $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(AssessmentShortAnswerQuestionRequest $request): object
    {
        $dto = AssessmentShortAnswerQuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssessmentShortAnswerQuestionRequest $request, AssessmentShortAnswerQuestion $question): object
    {
        $dto = AssessmentShortAnswerQuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(AssessmentShortAnswerQuestion $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function addAssessmentShortAnswerQuestionToQuestionBank(AssessmentShortAnswerQuestion $question): void
    {
        $this->repository->addAssessmentShortAnswerQuestionToQuestionBank($question->id);
    }
}
