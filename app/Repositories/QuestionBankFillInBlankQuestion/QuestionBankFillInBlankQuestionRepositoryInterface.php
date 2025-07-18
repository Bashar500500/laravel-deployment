<?php

namespace App\Repositories\QuestionBankFillInBlankQuestion;

use App\DataTransferObjects\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionDto;
use App\DataTransferObjects\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto;

interface QuestionBankFillInBlankQuestionRepositoryInterface
{
    public function all(QuestionBankFillInBlankQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionBankFillInBlankQuestionDto $dto): object;

    public function update(QuestionBankFillInBlankQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addQuestionBankFillInBlankQuestionToAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto $dto, int $id): void;

    public function removeQuestionBankFillInBlankQuestionFromAssessment(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto $dto, int $id): void;
}
