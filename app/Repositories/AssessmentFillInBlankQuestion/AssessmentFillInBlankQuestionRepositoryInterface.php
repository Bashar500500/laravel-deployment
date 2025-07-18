<?php

namespace App\Repositories\AssessmentFillInBlankQuestion;

use App\DataTransferObjects\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDto;

interface AssessmentFillInBlankQuestionRepositoryInterface
{
    public function all(AssessmentFillInBlankQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(AssessmentFillInBlankQuestionDto $dto): object;

    public function update(AssessmentFillInBlankQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addAssessmentFillInBlankQuestionToQuestionBank(int $id): void;
}
