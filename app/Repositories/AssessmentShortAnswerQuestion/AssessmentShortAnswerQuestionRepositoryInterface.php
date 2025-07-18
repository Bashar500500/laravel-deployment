<?php

namespace App\Repositories\AssessmentShortAnswerQuestion;

use App\DataTransferObjects\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionDto;

interface AssessmentShortAnswerQuestionRepositoryInterface
{
    public function all(AssessmentShortAnswerQuestionDto $dto): object;

    public function find(int $id): object;

    public function create(AssessmentShortAnswerQuestionDto $dto): object;

    public function update(AssessmentShortAnswerQuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function addAssessmentShortAnswerQuestionToQuestionBank(int $id): void;
}
