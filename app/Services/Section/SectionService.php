<?php

namespace App\Services\Section;

use App\Repositories\Section\SectionRepositoryInterface;
use App\Http\Requests\Section\SectionRequest;
use App\Models\Section\Section;
use App\DataTransferObjects\Section\SectionDto;

class SectionService
{
    public function __construct(
        protected SectionRepositoryInterface $repository,
    ) {}

    public function index(SectionRequest $request): object
    {
        $dto = SectionDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Section $section): object
    {
        return $this->repository->find($section->id);
    }

    public function store(SectionRequest $request): object
    {
        $dto = SectionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(SectionRequest $request, Section $section): object
    {
        $dto = SectionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $section->id);
    }

    public function destroy(Section $section): object
    {
        return $this->repository->delete($section->id);
    }

    public function view(Section $section, string $fileName): string
    {
        return $this->repository->view($section->id, $fileName);
    }

    public function download(Section $section): string
    {
        return $this->repository->download($section->id);
    }

    public function destroyAttachment(Section $section, string $fileName): void
    {
        $this->repository->deleteAttachment($section->id, $fileName);
    }
}
