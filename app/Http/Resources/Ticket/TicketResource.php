<?php

namespace App\Http\Resources\Ticket;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'date' => $this->date,
            'subject' => $this->subject,
            'priority' => $this->priority,
            'category' => $this->category,
            'status' => $this->status,
        ];
    }
}
