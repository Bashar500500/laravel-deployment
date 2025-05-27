<?php

namespace App\Services\TeachingHour;

use App\Repositories\TeachingHour\TeachingHourRepositoryInterface;
use App\Http\Requests\TeachingHour\TeachingHourRequest;
use App\Models\TeachingHour\TeachingHour;
use App\DataTransferObjects\TeachingHour\TeachingHourDto;

class TeachingHourService
{
    public function __construct(
        protected TeachingHourRepositoryInterface $repository,
    ) {}

    public function index(TeachingHourRequest $request): object
    {
        $dto = TeachingHourDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(TeachingHour $teachingHour): object
    {
        return $this->repository->find($teachingHour->id);
    }

    public function store(TeachingHourRequest $request): object
    {
        $dto = TeachingHourDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(TeachingHourRequest $request, TeachingHour $teachingHour): object
    {
        $dto = TeachingHourDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $teachingHour->id);
    }

    public function destroy(TeachingHour $teachingHour): object
    {
        return $this->repository->delete($teachingHour->id);
    }
}
