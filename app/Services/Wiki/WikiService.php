<?php

namespace App\Services\Wiki;

use App\Repositories\Wiki\WikiRepositoryInterface;
use App\Http\Requests\Wiki\WikiRequest;
use App\Models\Wiki\Wiki;
use App\DataTransferObjects\Wiki\WikiDto;
use Illuminate\Support\Facades\Auth;

class WikiService
{
    public function __construct(
        protected WikiRepositoryInterface $repository,
    ) {}

    public function index(WikiRequest $request): object
    {
        $dto = WikiDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Wiki $wiki): object
    {
        return $this->repository->find($wiki->id);
    }

    public function store(WikiRequest $request): object
    {
        $dto = WikiDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(WikiRequest $request, Wiki $wiki): object
    {
        $dto = WikiDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $wiki->id);
    }

    public function destroy(Wiki $wiki): object
    {
        return $this->repository->delete($wiki->id);
    }

    public function view(Wiki $wiki, string $fileName): string
    {
        return $this->repository->view($wiki->id, $fileName);
    }

    public function download(Wiki $wiki): string
    {
        return $this->repository->download($wiki->id);
    }

    public function destroyAttachment(Wiki $wiki, string $fileName): void
    {
        $this->repository->deleteAttachment($wiki->id, $fileName);
    }

    private function prepareStoreData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
