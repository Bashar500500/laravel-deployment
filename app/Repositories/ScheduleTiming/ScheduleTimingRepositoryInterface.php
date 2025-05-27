<?php

namespace App\Repositories\ScheduleTiming;

use App\DataTransferObjects\ScheduleTiming\ScheduleTimingDto;

interface ScheduleTimingRepositoryInterface
{
    public function all(ScheduleTimingDto $dto): object;

    public function find(int $id): object;

    public function create(ScheduleTimingDto $dto): object;

    public function update(ScheduleTimingDto $dto, int $id): object;

    public function delete(int $id): object;
}
