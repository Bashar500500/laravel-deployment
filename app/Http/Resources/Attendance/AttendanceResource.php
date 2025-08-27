<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'learningActivityTitle' => $this->whenLoaded('learningActivity')->title,
            'studentName' => $this->whenLoaded('student')->first_name .
                $this->whenLoaded('student')->last_name,
            'isPresent' => $this->is_present,
        ];
    }
}
