<?php

namespace App\Repositories\Message;

use App\DataTransferObjects\Message\MessageDto;

interface MessageRepositoryInterface
{
    public function all(MessageDto $dto): object;

    public function create(MessageDto $dto, array $data): object;

    public function update(MessageDto $dto, int $id): object;

    public function delete(int $id): object;
}
