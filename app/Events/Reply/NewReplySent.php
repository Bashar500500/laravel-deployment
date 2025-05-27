<?php

namespace App\Events\Reply;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reply\Reply;
use App\Http\Resources\Reply\ReplyResource;

class NewReplySent implements ShouldBroadcastNow, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /**
     * Create a new event instance.
     */
    public function __construct(private Reply $reply)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('reply.' . $this->reply->message->messageable_id . '.' .
                class_basename($this->reply->message->messageable_type)),
        ];
    }

    public function broadcastAs(): string
    {
        return 'reply.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => ReplyResource::make($this->reply),
        ];
    }
}
