<?php

namespace App\Repositories\TeachingHour;

use App\DataTransferObjects\TeachingHour\TeachingHourDto;

interface TeachingHourRepositoryInterface
{
    public function all(TeachingHourDto $dto): object;

    public function find(int $id): object;

    public function create(TeachingHourDto $dto): object;

    public function update(TeachingHourDto $dto, int $id): object;

    public function delete(int $id): object;
}
