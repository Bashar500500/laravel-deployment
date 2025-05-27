<?php

namespace App\Repositories\Reply;

use App\Repositories\BaseRepository;
use App\Models\Reply\Reply;
use App\DataTransferObjects\Reply\ReplyDto;
use Illuminate\Support\Facades\DB;
use App\Models\Message\Message;
use App\Models\Chat\Chat;

class ReplyRepository extends BaseRepository implements ReplyRepositoryInterface
{
    public function __construct(Reply $reply) {
        parent::__construct($reply);
    }

    public function create(ReplyDto $dto, array $data): object
    {
        $message = Message::find($dto->messageId);
        $chat = Chat::find($message->messageable_id);
        $reply = DB::transaction(function () use ($dto, $data, $chat, $message) {
            $message = $chat->message()->create([
                'user_id' => $data['userId'],
                'message' => $message->message,
            ]);

            $reply = (object) $this->model->create([
                'user_id' => $data['userId'],
                'message_id' => $message->id,
                'reply' => $dto->reply,
            ]);

            return $reply;
        });

        return (object) $reply->load('user', 'message');
    }

    public function update(ReplyDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $reply = DB::transaction(function () use ($dto, $model) {
            $reply = tap($model)->update([
                'reply' => $dto->reply,
            ]);

            return $reply;
        });

        return (object) $reply->load('user', 'message');
    }

    public function delete(int $id): object
    {
        $reply = (object) parent::find($id);

        $reply = DB::transaction(function () use ($reply, $id) {
            $reply->message->delete();
            return $reply;
        });

        return (object) $reply;
    }
}
