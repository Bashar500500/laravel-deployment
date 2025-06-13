<?php

namespace App\Repositories\CommunityAccess;

use App\DataTransferObjects\CommunityAccess\CommunityAccessDto;

interface CommunityAccessRepositoryInterface
{
    public function all(CommunityAccessDto $dto): object;

    public function find(int $id): object;

    public function create(CommunityAccessDto $dto): object;

    public function update(CommunityAccessDto $dto, int $id): object;

    public function delete(int $id): object;
}
