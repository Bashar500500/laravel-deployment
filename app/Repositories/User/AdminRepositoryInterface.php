<?php

namespace App\Repositories\User;

use App\DataTransferObjects\User\AdminDto;

interface AdminRepositoryInterface
{
    public function all(AdminDto $dto): object;

    public function find(int $id): object;

    public function create(AdminDto $dto): object;

    public function update(AdminDto $dto, int $id): object;

    public function delete(int $id): object;
}
