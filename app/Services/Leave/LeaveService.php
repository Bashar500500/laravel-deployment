<?php

namespace App\Services\Leave;

use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Http\Requests\Leave\LeaveRequest;
use App\Models\Leave\Leave;
use App\DataTransferObjects\Leave\LeaveDto;
use Illuminate\Support\Facades\Auth;

class LeaveService
{
    public function __construct(
        protected LeaveRepositoryInterface $repository,
    ) {}

    public function index(LeaveRequest $request): object
    {
        $dto = LeaveDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Leave $leave): object
    {
        return $this->repository->find($leave->id);
    }

    public function store(LeaveRequest $request): object
    {
        $dto = LeaveDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(LeaveRequest $request, Leave $leave): object
    {
        $dto = LeaveDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $leave->id);
    }

    public function destroy(Leave $leave): object
    {
        return $this->repository->delete($leave->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
