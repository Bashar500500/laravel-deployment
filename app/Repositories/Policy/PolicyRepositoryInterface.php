<?php

namespace App\Repositories\Policy;

use App\DataTransferObjects\Policy\PolicyDto;

interface PolicyRepositoryInterface
{
    public function all(PolicyDto $dto): object;

    public function find(int $id): object;

    public function create(PolicyDto $dto): object;

    public function update(PolicyDto $dto, int $id): object;

    public function delete(int $id): object;
}
