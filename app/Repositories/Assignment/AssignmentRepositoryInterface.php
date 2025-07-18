<?php

namespace App\Repositories\Assignment;

use App\DataTransferObjects\Assignment\AssignmentDto;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;

interface AssignmentRepositoryInterface
{
    public function all(AssignmentDto $dto): object;

    public function find(int $id): object;

    public function create(AssignmentDto $dto): object;

    public function update(AssignmentDto $dto, int $id): object;

    public function delete(int $id): object;

    public function submit(AssignmentSubmitDto $dto, array $data): object;
}
