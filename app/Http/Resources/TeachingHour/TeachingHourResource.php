<?php

namespace App\Http\Resources\TeachingHour;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachingHourResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                $this->whenLoaded('instructor')->last_name,
            'totalHours' => $this->total_hours,
            'completedHours' => $this->completed_hours,
            'upcoming' => $this->upcoming,
            'break' => $this->break,
            'status' => $this->status,
        ];
    }
}
