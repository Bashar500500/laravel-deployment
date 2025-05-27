<?php

namespace App\Repositories\Reply;

use App\DataTransferObjects\Reply\ReplyDto;

interface ReplyRepositoryInterface
{
    public function create(ReplyDto $dto, array $data): object;

    public function update(ReplyDto $dto, int $id): object;

    public function delete(int $id): object;
}
