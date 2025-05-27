<?php

namespace App\Services\Message;

use App\Repositories\Message\MessageRepositoryInterface;
use App\Http\Requests\Message\MessageRequest;
use App\Models\Message\Message;
use App\DataTransferObjects\Message\MessageDto;

class MessageService
{
    public function __construct(
        protected MessageRepositoryInterface $repository,
    ) {}

    public function index(MessageRequest $request): object
    {
        $dto = MessageDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function store(MessageRequest $request): object
    {
        $dto = MessageDto::fromStoreRequest($request);
        $data = $this->prepareStoreData();
        return $this->repository->create($dto, $data);
    }

    public function update(MessageRequest $request, Message $message): object
    {
        $dto = MessageDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $message->id);
    }

    public function destroy(Message $message): object
    {
        return $this->repository->delete($message->id);
    }

    private function prepareStoreData(): array
    {
        return [
            // 'userId' => auth()->user()->id
            'userId' => 1,
        ];
    }
}
