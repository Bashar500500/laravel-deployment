<?php

namespace App\Repositories\QuestionBankTrueOrFalseQuestion;

use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionDto;
use App\DataTransferObjects\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto;

interface QuestionBankTrueOrFalseQuestionRepositoryInterface
{
    public function all(QuestionBankTrueOrFalseQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionBankTrueOrFalseQuestionDto $dto): object;

    public function update(QuestionBankTrueOrFalseQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addQuestionBankTrueOrFalseQuestionToAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto $dto, int $id): void;

    public function removeQuestionBankTrueOrFalseQuestionFromAssessment(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto $dto, int $id): void;
}
