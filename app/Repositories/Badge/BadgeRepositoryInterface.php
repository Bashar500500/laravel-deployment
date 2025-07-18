<?php

namespace App\Repositories\Badge;

use App\DataTransferObjects\Badge\BadgeDto;

interface BadgeRepositoryInterface
{
    public function all(BadgeDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(BadgeDto $dto, array $data): object;

    public function update(BadgeDto $dto, int $id): object;

    public function delete(int $id): object;
}
