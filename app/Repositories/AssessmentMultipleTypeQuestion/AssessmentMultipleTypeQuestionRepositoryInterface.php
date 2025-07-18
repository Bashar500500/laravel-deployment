<?php

namespace App\Repositories\AssessmentMultipleTypeQuestion;

use App\DataTransferObjects\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionDto;

interface AssessmentMultipleTypeQuestionRepositoryInterface
{
    public function all(AssessmentMultipleTypeQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(AssessmentMultipleTypeQuestionDto $dto): object;

    public function update(AssessmentMultipleTypeQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addAssessmentMultipleTypeQuestionToQuestionBank(int $id): void;
}
