<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarStudentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'studentName' => $this->student?->first_name . ' ' . $this->student?->last_name,
        ];
    }
}
