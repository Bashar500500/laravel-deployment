<?php

namespace App\Repositories\Message;

use App\Repositories\BaseRepository;
use App\Models\Message\Message;
use App\DataTransferObjects\Message\MessageDto;
use App\Enums\Model\ModelTypePath;
use Illuminate\Support\Facades\DB;
use App\Models\Chat\Chat;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    public function __construct(Message $message) {
        parent::__construct($message);
    }

    public function all(MessageDto $dto): object
    {
        return (object) $this->model->where('messageable_type', ModelTypePath::Chat->getTypePath())
            ->where('messageable_id', $dto->chatId)
            ->with('user', 'replies')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function create(MessageDto $dto, array $data): object
    {
        $chat = Chat::find($dto->chatId);

        $message = DB::transaction(function () use ($dto, $chat, $data) {
            $message = $chat->message()->create([
                'user_id' => $data['userId'],
                'message' => $dto->message,
            ]);

            return $message;
        });

        return (object) $message->load('user', 'replies');
    }

    public function update(MessageDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $message = DB::transaction(function () use ($dto, $model) {
            $message = tap($model)->update([
                'message' => $dto->message,
            ]);

            return $message;
        });

        return (object) $message->load('user', 'replies');
    }

    public function delete(int $id): object
    {
        $message = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $message;
    }
}
