<?php

namespace App\Http\Resources\SupportTicket;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'username' => $this->whenLoaded('user')->first_name .
                ' ' . $this->whenLoaded('user')->last_name,
            'date' => $this->date,
            'subject' => $this->subject,
            'priority' => $this->priority,
            'category' => $this->category,
            'status' => $this->status,
        ];
    }
}
