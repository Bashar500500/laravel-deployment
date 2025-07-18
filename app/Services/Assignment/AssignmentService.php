<?php

namespace App\Services\Assignment;

use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Http\Requests\Assignment\AssignmentRequest;
use App\Models\Assignment\Assignment;
use App\DataTransferObjects\Assignment\AssignmentDto;
use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssignmentService
{
    public function __construct(
        protected AssignmentRepositoryInterface $repository,
    ) {}

    public function index(AssignmentRequest $request): object
    {
        $dto = AssignmentDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Assignment $assignment): object
    {
        return $this->repository->find($assignment->id);
    }

    public function store(AssignmentRequest $request): object
    {
        $dto = AssignmentDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(AssignmentRequest $request, Assignment $assignment): object
    {
        $dto = AssignmentDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $assignment->id);
    }

    public function destroy(Assignment $assignment): object
    {
        return $this->repository->delete($assignment->id);
    }

    public function submit(AssignmentSubmitRequest $request): object
    {
        $dto = AssignmentSubmitDto::fromRequest($request);
        $data = $this->prepareAssignmentSubmitData();
        return $this->repository->submit($dto, $data);
    }

    private function prepareAssignmentSubmitData(): array
    {
        return [
            'student' => Auth::user(),
        ];
    }
}
