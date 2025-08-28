<?php

namespace App\Services\InteractiveContent;

use App\Repositories\InteractiveContent\InteractiveContentRepositoryInterface;
use App\Http\Requests\InteractiveContent\InteractiveContentRequest;
use App\Models\InteractiveContent\InteractiveContent;
use App\DataTransferObjects\InteractiveContent\InteractiveContentDto;
use Illuminate\Support\Facades\Auth;

class InteractiveContentService
{
    public function __construct(
        protected InteractiveContentRepositoryInterface $repository,
    ) {}

    public function index(InteractiveContentRequest $request): object
    {
        $dto = InteractiveContentDto::fromIndexRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->all($dto, $data);
    }

    public function show(InteractiveContent $interactiveContent): object
    {
        return $this->repository->find($interactiveContent->id);
    }

    public function store(InteractiveContentRequest $request): object
    {
        $dto = InteractiveContentDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(InteractiveContentRequest $request, InteractiveContent $interactiveContent): object
    {
        $dto = InteractiveContentDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $interactiveContent->id);
    }

    public function destroy(InteractiveContent $interactiveContent): object
    {
        return $this->repository->delete($interactiveContent->id);
    }

    public function view(InteractiveContent $interactiveContent): string
    {
        return $this->repository->view($interactiveContent->id);
    }

    public function download(InteractiveContent $interactiveContent): string
    {
        return $this->repository->download($interactiveContent->id);
    }

    public function destroyAttachment(InteractiveContent $interactiveContent): void
    {
        $this->repository->deleteAttachment($interactiveContent->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
