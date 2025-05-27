<?php

namespace App\Services\Attendance;

use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Models\Attendance\Attendance;
use App\DataTransferObjects\Attendance\AttendanceDto;

class AttendanceService
{
    public function __construct(
        protected AttendanceRepositoryInterface $repository,
    ) {}

    public function index(AttendanceRequest $request): object
    {
        $dto = AttendanceDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Attendance $attendance): object
    {
        return $this->repository->find($attendance->id);
    }

    public function store(AttendanceRequest $request): object
    {
        $dto = AttendanceDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AttendanceRequest $request, Attendance $attendance): object
    {
        $dto = AttendanceDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $attendance->id);
    }

    public function destroy(Attendance $attendance): object
    {
        return $this->repository->delete($attendance->id);
    }
}
