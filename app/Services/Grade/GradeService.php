<?php

namespace App\Services\Grade;

use App\Repositories\Grade\GradeRepositoryInterface;
use App\Http\Requests\Grade\GradeRequest;
use App\Models\Grade\Grade;
use App\DataTransferObjects\Grade\GradeDto;

class GradeService
{
    public function __construct(
        protected GradeRepositoryInterface $repository,
    ) {}

    public function index(GradeRequest $request): object
    {
        $dto = GradeDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Grade $grade): object
    {
        return $this->repository->find($grade->id);
    }

    public function store(GradeRequest $request): object
    {
        $dto = GradeDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(GradeRequest $request, Grade $grade): object
    {
        $dto = GradeDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $grade->id);
    }

    public function destroy(Grade $grade): object
    {
        return $this->repository->delete($grade->id);
    }
}
