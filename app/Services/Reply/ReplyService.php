<?php

namespace App\Services\Reply;

use App\Repositories\Reply\ReplyRepositoryInterface;
use App\Http\Requests\Reply\ReplyRequest;
use App\Models\Reply\Reply;
use App\DataTransferObjects\Reply\ReplyDto;

class ReplyService
{
    public function __construct(
        protected ReplyRepositoryInterface $repository,
    ) {}

    public function store(ReplyRequest $request): object
    {
        $dto = ReplyDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(ReplyRequest $request, Reply $reply): object
    {
        $dto = ReplyDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $reply->id);
    }

    public function destroy(Reply $reply): object
    {
        return $this->repository->delete($reply->id);
    }

    private function prepareStoreData(): array
    {
        return [
            // 'userId' => auth()->user()->id
            'userId' => 1,
        ];
    }
}
