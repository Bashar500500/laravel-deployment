<?php

namespace App\Repositories\Attendance;

use App\DataTransferObjects\Attendance\AttendanceDto;

interface AttendanceRepositoryInterface
{
    public function all(AttendanceDto $dto): object;

    public function find(int $id): object;

    public function create(AttendanceDto $dto): object;

    public function update(AttendanceDto $dto, int $id): object;

    public function delete(int $id): object;
}
