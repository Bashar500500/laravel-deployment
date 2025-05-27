<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Message\MessageResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'last_message' => MessageResource::make($this->whenLoaded('lastMessage')),
            'direct_chats' => DirectChatResource::collection($this->whenLoaded('directChats')),
        ];
    }
}
