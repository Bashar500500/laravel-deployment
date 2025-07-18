<?php

namespace App\Repositories\Rule;

use App\DataTransferObjects\Rule\RuleDto;

interface RuleRepositoryInterface
{
    public function all(RuleDto $dto): object;

    public function find(int $id): object;

    public function create(RuleDto $dto): object;

    public function update(RuleDto $dto, int $id): object;

    public function delete(int $id): object;
}
