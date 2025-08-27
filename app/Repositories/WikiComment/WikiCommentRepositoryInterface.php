<?php

namespace App\Repositories\WikiComment;

use App\DataTransferObjects\WikiComment\WikiCommentDto;

interface WikiCommentRepositoryInterface
{
    public function all(WikiCommentDto $dto): object;

    public function find(int $id): object;

    public function create(WikiCommentDto $dto, array $data): object;

    public function update(WikiCommentDto $dto, int $id): object;

    public function delete(int $id): object;
}
