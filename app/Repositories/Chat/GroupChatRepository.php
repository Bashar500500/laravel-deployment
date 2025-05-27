<?php

namespace App\Repositories\Chat;

use App\Repositories\BaseRepository;
use App\Models\Chat\Chat;
use App\DataTransferObjects\Chat\ChatDto;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;

class GroupChatRepository extends BaseRepository implements ChatRepositoryInterface
{
    public function __construct(Chat $chat) {
        parent::__construct($chat);
    }

    public function all(ChatDto $dto): object
    {
        return (object) $this->model->where('type', $dto->type)
            // ->whereHas('messages')
            ->with('lastMessage.user')
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
            ->load('lastMessage.user');
    }

    public function create(ChatDto $dto, array $data): object
    {
        $previousConversaion = $this->getPreviousChat($data['lessonId']);

        if ($previousConversaion != null)
        {
            throw CustomException::alreadyExists(ModelName::Chat);
        }

        $chat = DB::transaction(function () use ($dto, $data) {
            $chat = (object) $this->model->create([
                'type' => $dto->type,
            ]);

            $chat->groupChat()->create([
                'lesson_id' => $data['lessonId'],
            ]);

            return $chat;
        });

        return (object) $chat;
    }

    public function delete(int $id): object
    {
        $groupChat = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $groupChat;
    }

    private function getPreviousChat(int $id): mixed
    {
        // $userId = auth()->user()->id;
        $userId = 1;

        return $this->model->where('type', 'group')
            ->whereHas('groupChat', function ($query) use ($id) {
                $query->where('lesson_id', $id);
            })
            ->first();
    }
}
