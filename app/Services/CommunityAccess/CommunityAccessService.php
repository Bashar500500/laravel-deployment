<?php

namespace App\Services\CommunityAccess;

use App\Repositories\CommunityAccess\CommunityAccessRepositoryInterface;
use App\Http\Requests\CommunityAccess\CommunityAccessRequest;
use App\Models\CommunityAccess\CommunityAccess;
use App\DataTransferObjects\CommunityAccess\CommunityAccessDto;

class CommunityAccessService
{
    public function __construct(
        protected CommunityAccessRepositoryInterface $repository,
    ) {}

    public function index(CommunityAccessRequest $request): object
    {
        $dto = CommunityAccessDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(CommunityAccess $communityAccess): object
    {
        return $this->repository->find($communityAccess->id);
    }

    public function store(CommunityAccessRequest $request): object
    {
        $dto = CommunityAccessDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(CommunityAccessRequest $request, CommunityAccess $communityAccess): object
    {
        $dto = CommunityAccessDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $communityAccess->id);
    }

    public function destroy(CommunityAccess $communityAccess): object
    {
        return $this->repository->delete($communityAccess->id);
    }
}
