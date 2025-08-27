<?php

namespace App\Repositories\Prerequisite;

use App\DataTransferObjects\Prerequisite\PrerequisiteDto;

interface PrerequisiteRepositoryInterface
{
    public function all(PrerequisiteDto $dto): object;

    public function find(int $id): object;

    public function create(PrerequisiteDto $dto, array $data): object;

    public function update(PrerequisiteDto $dto, int $id): object;

    public function delete(int $id): object;
}
