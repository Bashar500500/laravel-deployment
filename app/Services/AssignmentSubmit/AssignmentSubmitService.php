<?php

namespace App\Services\AssignmentSubmit;

use App\Factories\AssignmentSubmit\AssignmentSubmitRepositoryFactory;
use App\Http\Requests\AssignmentSubmit\AssignmentSubmitRequest;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmitService
{
    public function __construct(
        protected AssignmentSubmitRepositoryFactory $factory,
    ) {}

    public function index(AssignmentSubmitRequest $request): object
    {
        $dto = AssignmentSubmitDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndUpdateData();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto, $data);
    }

    public function show(AssignmentSubmit $assignmentSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($assignmentSubmit->id);
    }

    public function update(AssignmentSubmitRequest $request, AssignmentSubmit $assignmentSubmit): object
    {
        $dto = AssignmentSubmitDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndUpdateData();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $assignmentSubmit->id, $data);
    }

    public function destroy(AssignmentSubmit $assignmentSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($assignmentSubmit->id);
    }

    public function view(AssignmentSubmit $assignmentSubmit, string $fileName): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($assignmentSubmit->id, $fileName);
    }

    public function download(AssignmentSubmit $assignmentSubmit): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($assignmentSubmit->id);
    }

    private function prepareIndexAndUpdateData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
