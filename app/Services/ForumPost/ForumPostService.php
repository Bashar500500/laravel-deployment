<?php

namespace App\Services\ForumPost;

use App\Repositories\ForumPost\ForumPostRepositoryInterface;
use App\Http\Requests\ForumPost\ForumPostRequest;
use App\Models\ForumPost\ForumPost;
use App\DataTransferObjects\ForumPost\ForumPostDto;
use Illuminate\Support\Facades\Auth;

class ForumPostService
{
    public function __construct(
        protected ForumPostRepositoryInterface $repository,
    ) {}

    public function index(ForumPostRequest $request): object
    {
        $dto = ForumPostDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(ForumPost $forumPost): object
    {
        return $this->repository->find($forumPost->id);
    }

    public function store(ForumPostRequest $request): object
    {
        $dto = ForumPostDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndIndexData();
        return $this->repository->create($dto, $data);
    }

    public function update(ForumPostRequest $request, ForumPost $forumPost): object
    {
        $dto = ForumPostDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $forumPost->id);
    }

    public function destroy(ForumPost $forumPost): object
    {
        return $this->repository->delete($forumPost->id);
    }

    private function prepareStoreAndIndexData(): array
    {
        return [
            'studentId' => Auth::user()->id,
        ];
    }
}
