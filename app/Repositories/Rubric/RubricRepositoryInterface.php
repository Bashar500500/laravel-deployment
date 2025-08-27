<?php

namespace App\Repositories\Rubric;

use App\DataTransferObjects\Rubric\RubricDto;

interface RubricRepositoryInterface
{
    public function all(RubricDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(RubricDto $dto, array $data): object;

    public function update(RubricDto $dto, int $id): object;

    public function delete(int $id): object;
}
