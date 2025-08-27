<?php

namespace App\Services\Plagiarism;

use App\Repositories\Plagiarism\PlagiarismRepositoryInterface;
use App\Http\Requests\Plagiarism\PlagiarismRequest;
use App\Models\Plagiarism\Plagiarism;
use App\DataTransferObjects\Plagiarism\PlagiarismDto;

class PlagiarismService
{
    public function __construct(
        protected PlagiarismRepositoryInterface $repository,
    ) {}

    public function index(PlagiarismRequest $request): object
    {
        $dto = PlagiarismDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(Plagiarism $plagiarism): object
    {
        return $this->repository->find($plagiarism->id);
    }

    public function update(PlagiarismRequest $request, Plagiarism $plagiarism): object
    {
        $dto = PlagiarismDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $plagiarism->id);
    }

    public function destroy(Plagiarism $plagiarism): object
    {
        return $this->repository->delete($plagiarism->id);
    }
}
