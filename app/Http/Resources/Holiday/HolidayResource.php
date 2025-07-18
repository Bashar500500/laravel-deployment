<?php

namespace App\Http\Resources\Holiday;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                ' ' . $this->whenLoaded('instructor')->last_name,
            'title' => $this->title,
            'date' => $this->date,
            'day' => $this->day,
        ];
    }
}
