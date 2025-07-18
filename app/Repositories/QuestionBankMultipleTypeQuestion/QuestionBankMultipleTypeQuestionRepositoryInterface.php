<?php

namespace App\Repositories\QuestionBankMultipleTypeQuestion;

use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionDto;
use App\DataTransferObjects\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto;

interface QuestionBankMultipleTypeQuestionRepositoryInterface
{
    public function all(QuestionBankMultipleTypeQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionBankMultipleTypeQuestionDto $dto): object;

    public function update(QuestionBankMultipleTypeQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addQuestionBankMultipleTypeQuestionToAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto $dto, int $id): void;

    public function removeQuestionBankMultipleTypeQuestionFromAssessment(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto $dto, int $id): void;
}
