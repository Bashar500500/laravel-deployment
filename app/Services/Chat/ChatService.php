<?php

namespace App\Services\Chat;

use App\Factories\Chat\ChatRepositoryFactory;
use App\Models\Chat\Chat;
use App\Http\Requests\Chat\ChatRequest;
use App\DataTransferObjects\Chat\ChatDto;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Chat\ChatType;
use App\Enums\Exception\ForbiddenExceptionMessage;

class ChatService
{
    public function __construct(
        protected ChatRepositoryFactory $factory,
    ) {}

    public function index(ChatRequest $request): object
    {
        $dto = ChatDto::fromIndexRequest($request);
        $repository = $this->factory->make($dto->type);
        return $repository->all($dto);
    }

    public function show(Chat $chat): object
    {
        $repository = $this->factory->make($chat->type);
        return $repository->find($chat->id);
    }

    public function store(ChatRequest $request): object
    {
        $dto = ChatDto::fromStoreRequest($request);
        $data = $this->prepareStoreData($dto);

        if ($dto->type == ChatType::Direct)
        {
            if ($data['userId'] == $data['otherUserId'])
            {
                throw CustomException::forbidden(ModelName::Chat, ForbiddenExceptionMessage::Chat);
            }
        }

        $repository = $this->factory->make($dto->type);
        return $repository->create($dto, $data);
    }

    public function destroy(Chat $chat): object
    {
        $repository = $this->factory->make($chat->type);
        return $repository->delete($chat->id);
    }

    private function prepareStoreData(ChatDto $dto): array
    {
        switch ($dto->type)
        {
            case ChatType::Direct:
                $otherUserId = (int) $dto->issuerId;

                return [
                    'otherUserId' => $otherUserId,
                    // 'userId' => auth()->user()->id
                    'userId' => 1,
                ];
            default:
                $lessonId = (int) $dto->issuerId;

                return [
                    'lessonId' => $lessonId,
                ];
        }
    }
}
