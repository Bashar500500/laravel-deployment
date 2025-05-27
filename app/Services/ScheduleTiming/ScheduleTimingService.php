<?php

namespace App\Services\ScheduleTiming;

use App\Repositories\ScheduleTiming\ScheduleTimingRepositoryInterface;
use App\Http\Requests\ScheduleTiming\ScheduleTimingRequest;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\DataTransferObjects\ScheduleTiming\ScheduleTimingDto;

class ScheduleTimingService
{
    public function __construct(
        protected ScheduleTimingRepositoryInterface $repository,
    ) {}

    public function index(ScheduleTimingRequest $request): object
    {
        $dto = ScheduleTimingDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(ScheduleTiming $scheduleTiming): object
    {
        return $this->repository->find($scheduleTiming->id);
    }

    public function store(ScheduleTimingRequest $request): object
    {
        $dto = ScheduleTimingDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(ScheduleTimingRequest $request, ScheduleTiming $scheduleTiming): object
    {
        $dto = ScheduleTimingDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $scheduleTiming->id);
    }

    public function destroy(ScheduleTiming $scheduleTiming): object
    {
        return $this->repository->delete($scheduleTiming->id);
    }
}
