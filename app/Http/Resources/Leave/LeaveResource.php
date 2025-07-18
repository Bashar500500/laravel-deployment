<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                ' ' . $this->whenLoaded('instructor')->last_name,
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
            'numberOfDays' => $this->number_of_days,
            'reason' => $this->reason,
            'status' => $this->status,
        ];
    }
}
