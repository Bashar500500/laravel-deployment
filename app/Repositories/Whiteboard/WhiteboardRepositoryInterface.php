<?php

namespace App\Repositories\Whiteboard;

use App\DataTransferObjects\Whiteboard\WhiteboardDto;

interface WhiteboardRepositoryInterface
{
    public function all(WhiteboardDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(WhiteboardDto $dto, array $data): object;

    public function update(WhiteboardDto $dto, int $id): object;

    public function delete(int $id): object;
}
