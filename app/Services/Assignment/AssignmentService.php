<?php

namespace App\Services\Assignment;

use App\Factories\Assignment\AssignmentRepositoryFactory;
use App\Http\Requests\Assignment\AssignmentRequest;
use App\Models\Assignment\Assignment;
use App\DataTransferObjects\Assignment\AssignmentDto;
use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssignmentService
{
    public function __construct(
        protected AssignmentRepositoryFactory $factory,
    ) {}

    public function index(AssignmentRequest $request): object
    {
        $dto = AssignmentDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto);
    }

    public function show(Assignment $assignment): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($assignment->id);
    }

    public function store(AssignmentRequest $request): object
    {
        $dto = AssignmentDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto);
    }

    public function update(AssignmentRequest $request, Assignment $assignment): object
    {
        $dto = AssignmentDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $assignment->id);
    }

    public function destroy(Assignment $assignment): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($assignment->id);
    }

    public function view(Assignment $assignment, string $fileName): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($assignment->id, $fileName);
    }

    public function download(Assignment $assignment): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($assignment->id);
    }

    public function destroyAttachment(Assignment $assignment, string $fileName): void
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->deleteAttachment($assignment->id, $fileName);
    }

    public function submit(AssignmentSubmitRequest $request): object
    {
        $dto = AssignmentSubmitDto::fromRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareAssignmentSubmitData();
        $repository = $this->factory->make($role[0]);
        return $repository->submit($dto, $data);
    }

    private function prepareAssignmentSubmitData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
