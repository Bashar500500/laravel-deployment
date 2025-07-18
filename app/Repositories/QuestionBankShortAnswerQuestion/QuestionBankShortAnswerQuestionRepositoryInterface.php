<?php

namespace App\Repositories\QuestionBankShortAnswerQuestion;

use App\DataTransferObjects\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionDto;
use App\DataTransferObjects\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto;

interface QuestionBankShortAnswerQuestionRepositoryInterface
{
    public function all(QuestionBankShortAnswerQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionBankShortAnswerQuestionDto $dto): object;

    public function update(QuestionBankShortAnswerQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addQuestionBankShortAnswerQuestionToAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto $dto, int $id): void;

    public function removeQuestionBankShortAnswerQuestionFromAssessment(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto $dto, int $id): void;
}
