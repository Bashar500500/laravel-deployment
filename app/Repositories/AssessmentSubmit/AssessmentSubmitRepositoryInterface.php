<?php

namespace App\Repositories\AssessmentSubmit;

use App\DataTransferObjects\AssessmentSubmit\AssessmentSubmitDto;

interface AssessmentSubmitRepositoryInterface
{
    public function all(AssessmentSubmitDto $dto, array $data): object;

    public function find(int $id): object;

    public function update(AssessmentSubmitDto $dto, int $id): object;

    public function delete(int $id): object;
}
