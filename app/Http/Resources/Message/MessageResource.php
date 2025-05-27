<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Reply\ReplyResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'messageable_type' => $this->messageable_type,
            'messageable_id' => $this->messageable_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'replies' => ReplyResource::collection($this->whenLoaded('replies')),
        ];
    }
}
