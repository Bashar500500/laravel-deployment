<?php

namespace App\Services\LearningGap;

use App\Repositories\learningGap\learningGapRepositoryInterface;
use App\Http\Requests\learningGap\learningGapRequest;
use App\Models\learningGap\learningGap;
use App\DataTransferObjects\learningGap\learningGapDto;
use Illuminate\Support\Facades\Auth;

class learningGapService
{
    public function __construct(
        protected learningGapRepositoryInterface $repository,
    ) {}

    public function index(learningGapRequest $request): object
    {
        $dto = learningGapDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(learningGap $learningGap): object
    {
        return $this->repository->find($learningGap->id);
    }

    public function store(learningGapRequest $request): object
    {
        $dto = learningGapDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(learningGapRequest $request, learningGap $learningGap): object
    {
        $dto = learningGapDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $learningGap->id);
    }

    public function destroy(learningGap $learningGap): object
    {
        return $this->repository->delete($learningGap->id);
    }
}
