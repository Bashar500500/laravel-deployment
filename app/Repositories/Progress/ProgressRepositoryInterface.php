<?php

namespace App\Repositories\Progress;

use App\DataTransferObjects\Progress\ProgressDto;

interface ProgressRepositoryInterface
{
    public function all(ProgressDto $dto): object;

    public function find(int $id): object;

    public function create(ProgressDto $dto): object;

    public function update(ProgressDto $dto, int $id): object;

    public function delete(int $id): object;
}
