<?php

namespace App\Services\WikiComment;

use App\Repositories\WikiComment\WikiCommentRepositoryInterface;
use App\Http\Requests\WikiComment\WikiCommentRequest;
use App\Models\WikiComment\WikiComment;
use App\DataTransferObjects\WikiComment\WikiCommentDto;
use Illuminate\Support\Facades\Auth;

class WikiCommentService
{
    public function __construct(
        protected WikiCommentRepositoryInterface $repository,
    ) {}

    public function index(WikiCommentRequest $request): object
    {
        $dto = WikiCommentDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(WikiComment $wikiComment): object
    {
        return $this->repository->find($wikiComment->id);
    }

    public function store(WikiCommentRequest $request): object
    {
        $dto = WikiCommentDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(WikiCommentRequest $request, WikiComment $wikiComment): object
    {
        $dto = WikiCommentDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $wikiComment->id);
    }

    public function destroy(WikiComment $wikiComment): object
    {
        return $this->repository->delete($wikiComment->id);
    }

    private function prepareStoreData(): array
    {
        return [
            'userId' => Auth::user()->id,
        ];
    }
}
