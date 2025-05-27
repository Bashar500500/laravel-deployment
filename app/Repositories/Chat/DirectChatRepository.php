<?php

namespace App\Repositories\Chat;

use App\Repositories\BaseRepository;
use App\Models\Chat\Chat;
use App\DataTransferObjects\Chat\ChatDto;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;

class DirectChatRepository extends BaseRepository implements ChatRepositoryInterface
{
    public function __construct(Chat $chat) {
        parent::__construct($chat);
    }

    public function all(ChatDto $dto): object
    {
        return (object) $this->model->where('type', $dto->type)
            // ->hasDirectChat(auth()->user()->id)
            // ->whereHas('messages')
            ->with('lastMessage.user', 'directChats.user')
            ->latest('updated_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id)
            ->load('lastMessage.user', 'directChats.user');
    }

    public function create(ChatDto $dto, array $data): object
    {
        $previousConversaion = $this->getPreviousChat($data['otherUserId']);

        if ($previousConversaion != null)
        {
            throw CustomException::alreadyExists(ModelName::Chat);
        }

        $chat = DB::transaction(function () use ($dto, $data) {
            $chat = (object) $this->model->create([
                'type' => $dto->type
            ]);

            $chat->directChats()->createMany([
                [
                    'user_id' => $data['userId']
                ],
                [
                    'user_id' => $data['otherUserId']
                ],
            ]);

            return $chat;
        });

        return (object) $chat->load('directChats.user');
    }

    public function delete(int $id): object
    {
        $directChat = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $directChat;
    }

    private function getPreviousChat(int $id): mixed
    {
        // $userId = auth()->user()->id;
        $userId = 1;

        return $this->model->where('type', 'direct')
            ->whereHas('directChats', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('directChats', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->first();
    }
}
