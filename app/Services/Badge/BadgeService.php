<?php

namespace App\Services\Badge;

use App\Repositories\Badge\BadgeRepositoryInterface;
use App\Http\Requests\Badge\BadgeRequest;
use App\Models\Badge\Badge;
use App\DataTransferObjects\Badge\BadgeDto;
use Illuminate\Support\Facades\Auth;

class BadgeService
{
    public function __construct(
        protected BadgeRepositoryInterface $repository,
    ) {}

    public function index(BadgeRequest $request): object
    {
        $dto = BadgeDto::fromIndexRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->all($dto, $data);
    }

    public function show(Badge $badge): object
    {
        return $this->repository->find($badge->id);
    }

    public function store(BadgeRequest $request): object
    {
        $dto = BadgeDto::fromStoreRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(BadgeRequest $request, Badge $badge): object
    {
        $dto = BadgeDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $badge->id);
    }

    public function destroy(Badge $badge): object
    {
        return $this->repository->delete($badge->id);
    }

    private function prepareIndexAndStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
