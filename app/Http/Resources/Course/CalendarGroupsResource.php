<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarGroupsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->group->name,
            'students' => CalendarStudentsResource::collection(collect($this->group?->students)),
        ];
    }
}
