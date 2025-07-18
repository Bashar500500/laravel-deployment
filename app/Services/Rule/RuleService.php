<?php

namespace App\Services\Rule;

use App\Repositories\Rule\RuleRepositoryInterface;
use App\Http\Requests\Rule\RuleRequest;
use App\Models\Rule\Rule;
use App\DataTransferObjects\Rule\RuleDto;

class RuleService
{
    public function __construct(
        protected RuleRepositoryInterface $repository,
    ) {}

    public function index(RuleRequest $request): object
    {
        $dto = RuleDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Rule $rule): object
    {
        return $this->repository->find($rule->id);
    }

    public function store(RuleRequest $request): object
    {
        $dto = RuleDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(RuleRequest $request, Rule $rule): object
    {
        $dto = RuleDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $rule->id);
    }

    public function destroy(Rule $rule): object
    {
        return $this->repository->delete($rule->id);
    }
}
