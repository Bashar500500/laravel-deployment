<?php

namespace App\Http\Resources\Reply;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Message\MessageResource;

class ReplyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'message_id' => $this->message_id,
            'reply' => $this->reply,
            'user' => UserResource::make($this->whenLoaded('user')),
            'message' => MessageResource::make($this->whenLoaded('message')),
        ];
    }
}
