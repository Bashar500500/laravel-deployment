<?php

namespace App\Repositories\Chat;

use App\DataTransferObjects\Chat\ChatDto;

interface ChatRepositoryInterface
{
    public function all(ChatDto $dto): object;

    public function find(int $id): object;

    public function create(ChatDto $dto, array $data): object;

    public function delete(int $id): object;
}
