<?php

namespace App\Services\Prerequisite;

use App\Repositories\Prerequisite\PrerequisiteRepositoryInterface;
use App\Http\Requests\Prerequisite\PrerequisiteRequest;
use App\Models\Prerequisite\Prerequisite;
use App\DataTransferObjects\Prerequisite\PrerequisiteDto;
use Illuminate\Support\Facades\Auth;

class PrerequisiteService
{
    public function __construct(
        protected PrerequisiteRepositoryInterface $repository,
    ) {}

    public function index(PrerequisiteRequest $request): object
    {
        $dto = PrerequisiteDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Prerequisite $prerequisite): object
    {
        return $this->repository->find($prerequisite->id);
    }

    public function store(PrerequisiteRequest $request): object
    {
        $dto = PrerequisiteDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(PrerequisiteRequest $request, Prerequisite $prerequisite): object
    {
        $dto = PrerequisiteDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $prerequisite->id);
    }

    public function destroy(Prerequisite $prerequisite): object
    {
        return $this->repository->delete($prerequisite->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
