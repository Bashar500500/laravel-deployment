<?php

namespace App\Services\ProjectSubmit;

use App\Factories\ProjectSubmit\ProjectSubmitRepositoryFactory;
use App\Http\Requests\ProjectSubmit\ProjectSubmitRequest;
use App\Models\ProjectSubmit\ProjectSubmit;
use App\DataTransferObjects\ProjectSubmit\ProjectSubmitDto;
use Illuminate\Support\Facades\Auth;

class ProjectSubmitService
{
    public function __construct(
        protected ProjectSubmitRepositoryFactory $factory,
    ) {}

    public function index(ProjectSubmitRequest $request): object
    {
        $dto = ProjectSubmitDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->all($dto);
    }

    public function show(ProjectSubmit $projectSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($projectSubmit->id);
    }

    public function update(ProjectSubmitRequest $request, ProjectSubmit $projectSubmit): object
    {
        $dto = ProjectSubmitDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, $projectSubmit->id);
    }

    public function destroy(ProjectSubmit $projectSubmit): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete($projectSubmit->id);
    }

    public function view(ProjectSubmit $projectSubmit, string $fileName): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->view($projectSubmit->id, $fileName);
    }

    public function download(ProjectSubmit $projectSubmit): string
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->download($projectSubmit->id);
    }
}
