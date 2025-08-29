<?php

namespace App\Repositories\ForumPost;

use App\DataTransferObjects\ForumPost\ForumPostDto;

interface ForumPostRepositoryInterface
{
    public function all(ForumPostDto $dto): object;

    public function find(int $id): object;

    public function create(ForumPostDto $dto, array $data): object;

    public function update(ForumPostDto $dto, int $id): object;

    public function delete(int $id): object;
}
