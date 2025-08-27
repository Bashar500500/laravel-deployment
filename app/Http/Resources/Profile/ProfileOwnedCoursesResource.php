<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileOwnedCoursesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'name' => $this->name,
            'description' => $this->description,
            'categoryId' => $this->category_id,
            'language' => $this->language,
            'level' => $this->level,
            'timezone' => $this->timezone,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'status' => $this->status,
            'duration' => $this->duration,
            'price' => $this->price,
        ];
    }
}
