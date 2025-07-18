<?php

namespace App\Repositories\Holiday;

use App\DataTransferObjects\Holiday\HolidayDto;

interface HolidayRepositoryInterface
{
    public function all(HolidayDto $dto): object;

    public function find(int $id): object;

    public function create(HolidayDto $dto, array $data): object;

    public function update(HolidayDto $dto, int $id): object;

    public function delete(int $id): object;
}
