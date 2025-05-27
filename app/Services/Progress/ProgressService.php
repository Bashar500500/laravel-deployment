<?php

namespace App\Services\Progress;

use App\Repositories\Progress\ProgressRepositoryInterface;
use App\Http\Requests\Progress\ProgressRequest;
use App\Models\Progress\Progress;
use App\DataTransferObjects\Progress\ProgressDto;

class ProgressService
{
    public function __construct(
        protected ProgressRepositoryInterface $repository,
    ) {}

    public function index(ProgressRequest $request): object
    {
        $dto = ProgressDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Progress $progress): object
    {
        return $this->repository->find($progress->id);
    }

    public function store(ProgressRequest $request): object
    {
        $dto = ProgressDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(ProgressRequest $request, Progress $progress): object
    {
        $dto = ProgressDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $progress->id);
    }

    public function destroy(Progress $progress): object
    {
        return $this->repository->delete($progress->id);
    }
}
