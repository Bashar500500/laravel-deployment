<?php

namespace App\Repositories\Grade;

use App\DataTransferObjects\Grade\GradeDto;

interface GradeRepositoryInterface
{
    public function all(GradeDto $dto): object;

    public function find(int $id): object;

    public function create(GradeDto $dto): object;

    public function update(GradeDto $dto, int $id): object;

    public function delete(int $id): object;
}
