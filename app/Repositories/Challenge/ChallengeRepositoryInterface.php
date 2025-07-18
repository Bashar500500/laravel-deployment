<?php

namespace App\Repositories\Challenge;

use App\DataTransferObjects\Challenge\ChallengeDto;

interface ChallengeRepositoryInterface
{
    public function all(ChallengeDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(ChallengeDto $dto, array $data): object;

    public function update(ChallengeDto $dto, int $id): object;

    public function delete(int $id): object;

    public function join(int $id, array $data): void;

    public function leave(int $id, array $data): void;
}
