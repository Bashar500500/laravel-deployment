<?php

namespace App\Repositories\AssessmentTrueOrFalseQuestion;

use App\DataTransferObjects\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDto;

interface AssessmentTrueOrFalseQuestionRepositoryInterface
{
    public function all(AssessmentTrueOrFalseQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(AssessmentTrueOrFalseQuestionDto $dto): object;

    public function update(AssessmentTrueOrFalseQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addAssessmentTrueOrFalseQuestionToQuestionBank(int $id): void;
}
