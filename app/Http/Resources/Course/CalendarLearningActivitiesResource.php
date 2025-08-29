<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarLearningActivitiesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'start' => $this->availability_start,
            'end' => $this->availability_end,
            'url' => $this->content_data['captions']['url'],
            'groups' => CalendarGroupsResource::collection(collect($this->section)->load('sectionEventGroups')),
        ];
    }
}
