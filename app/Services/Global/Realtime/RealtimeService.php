<?php

namespace App\Services\Global\Realtime;

use App\Models\Message\Message;
use App\Models\Reply\Reply;
use App\Events\Message\NewMessageSent;
use App\Events\Reply\NewReplySent;

class RealtimeService
{
    public function sendRealtimeMessage(Message $message): void
    {
        broadcast(new NewMessageSent($message))->toOthers();
    }

    public function sendRealtimeReply(Reply $reply): void
    {
        broadcast(new NewReplySent($reply))->toOthers();
    }
}
