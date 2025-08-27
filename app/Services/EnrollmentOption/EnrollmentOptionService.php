<?php

namespace App\Services\EnrollmentOption;

use App\Repositories\EnrollmentOption\EnrollmentOptionRepositoryInterface;
use App\Http\Requests\EnrollmentOption\EnrollmentOptionRequest;
use App\Models\EnrollmentOption\EnrollmentOption;
use App\DataTransferObjects\EnrollmentOption\EnrollmentOptionDto;

class EnrollmentOptionService
{
    public function __construct(
        protected EnrollmentOptionRepositoryInterface $repository,
    ) {}

    public function index(EnrollmentOptionRequest $request): object
    {
        $dto = EnrollmentOptionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(EnrollmentOption $enrollmentOption): object
    {
        return $this->repository->find($enrollmentOption->id);
    }

    public function store(EnrollmentOptionRequest $request): object
    {
        $dto = EnrollmentOptionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(EnrollmentOptionRequest $request, EnrollmentOption $enrollmentOption): object
    {
        $dto = EnrollmentOptionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $enrollmentOption->id);
    }

    public function destroy(EnrollmentOption $enrollmentOption): object
    {
        return $this->repository->delete($enrollmentOption->id);
    }
}
