<?php

namespace App\Services\Policy;

use App\Repositories\Policy\PolicyRepositoryInterface;
use App\Http\Requests\Policy\PolicyRequest;
use App\Models\Policy\Policy;
use App\DataTransferObjects\Policy\PolicyDto;

class PolicyService
{
    public function __construct(
        protected PolicyRepositoryInterface $repository,
    ) {}

    public function index(PolicyRequest $request): object
    {
        $dto = PolicyDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Policy $policy): object
    {
        return $this->repository->find($policy->id);
    }

    public function store(PolicyRequest $request): object
    {
        $dto = PolicyDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(PolicyRequest $request, Policy $policy): object
    {
        $dto = PolicyDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $policy->id);
    }

    public function destroy(Policy $policy): object
    {
        return $this->repository->delete($policy->id);
    }
}
