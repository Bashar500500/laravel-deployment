<?php

namespace App\Services\TimeLimit;

use App\Repositories\TimeLimit\TimeLimitRepositoryInterface;
use App\Http\Requests\TimeLimit\TimeLimitRequest;
use App\Models\TimeLimit\TimeLimit;
use App\DataTransferObjects\TimeLimit\TimeLimitDto;
use Illuminate\Support\Facades\Auth;

class TimeLimitService
{
    public function __construct(
        protected TimeLimitRepositoryInterface $repository,
    ) {}

    public function index(TimeLimitRequest $request): object
    {
        $dto = TimeLimitDto::fromIndexRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->all($dto, $data);
    }

    public function show(TimeLimit $timeLimit): object
    {
        return $this->repository->find($timeLimit->id);
    }

    public function store(TimeLimitRequest $request): object
    {
        $dto = TimeLimitDto::fromStoreRequest($request);
        $data = $this->prepareIndexAndStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(TimeLimitRequest $request, TimeLimit $timeLimit): object
    {
        $dto = TimeLimitDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $timeLimit->id);
    }

    public function destroy(TimeLimit $timeLimit): object
    {
        return $this->repository->delete($timeLimit->id);
    }

    private function prepareIndexAndStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
