<?php

namespace App\Repositories\TimeLimit;

use App\DataTransferObjects\TimeLimit\TimeLimitDto;

interface TimeLimitRepositoryInterface
{
    public function all(TimeLimitDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(TimeLimitDto $dto, array $data): object;

    public function update(TimeLimitDto $dto, int $id): object;

    public function delete(int $id): object;
}
