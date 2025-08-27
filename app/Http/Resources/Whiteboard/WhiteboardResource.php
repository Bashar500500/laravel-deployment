<?php

namespace App\Http\Resources\Whiteboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WhiteboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'courseId' => $this->course_id,
            'courseName' => $this->whenLoaded('course')->name,
            'name' => $this->name,
        ];
    }
}
