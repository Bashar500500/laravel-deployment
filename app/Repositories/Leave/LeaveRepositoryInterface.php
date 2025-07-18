<?php

namespace App\Repositories\Leave;

use App\DataTransferObjects\Leave\LeaveDto;

interface LeaveRepositoryInterface
{
    public function all(LeaveDto $dto): object;

    public function find(int $id): object;

    public function create(LeaveDto $dto, array $data): object;

    public function update(LeaveDto $dto, int $id): object;

    public function delete(int $id): object;
}
