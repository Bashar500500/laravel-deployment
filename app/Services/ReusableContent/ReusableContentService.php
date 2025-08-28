<?php

namespace App\Services\ReusableContent;

use App\Repositories\ReusableContent\ReusableContentRepositoryInterface;
use App\Http\Requests\ReusableContent\ReusableContentRequest;
use App\Models\ReusableContent\ReusableContent;
use App\DataTransferObjects\ReusableContent\ReusableContentDto;
use Illuminate\Support\Facades\Auth;

class ReusableContentService
{
    public function __construct(
        protected ReusableContentRepositoryInterface $repository,
    ) {}

    public function index(ReusableContentRequest $request): object
    {
        $dto = ReusableContentDto::fromIndexRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->all($dto, $data);
    }

    public function show(ReusableContent $reusableContent): object
    {
        return $this->repository->find($reusableContent->id);
    }

    public function store(ReusableContentRequest $request): object
    {
        $dto = ReusableContentDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(ReusableContentRequest $request, ReusableContent $reusableContent): object
    {
        $dto = ReusableContentDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $reusableContent->id);
    }

    public function destroy(ReusableContent $reusableContent): object
    {
        return $this->repository->delete($reusableContent->id);
    }

    public function view(ReusableContent $reusableContent): string
    {
        return $this->repository->view($reusableContent->id);
    }

    public function download(ReusableContent $reusableContent): string
    {
        return $this->repository->download($reusableContent->id);
    }

    public function destroyAttachment(ReusableContent $reusableContent): void
    {
        $this->repository->deleteAttachment($reusableContent->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'instructorId' => Auth::user()->id,
        ];
    }
}
