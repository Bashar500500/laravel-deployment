<?php

namespace App\Services\User;
use App\Repositories\User\AdminRepositoryInterface;
use App\Http\Requests\User\AdminRequest;
use App\Models\User\User;
use App\DataTransferObjects\User\AdminDto;

class AdminService
{
    public function __construct(
        protected AdminRepositoryInterface $repository
    ) {}

    public function index(AdminRequest $request): object
    {
        $dto = AdminDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(User $user): object
    {
        return $this->repository->find($user->id);
    }

    public function store(AdminRequest $request): object
    {
        $dto = AdminDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AdminRequest $request, User $user): object
    {
        $dto = AdminDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $user->id);
    }

    public function destroy(User $user): object
    {
        return $this->repository->delete($user->id);
    }
}
