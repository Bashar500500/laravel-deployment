<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseName' => $this->whenLoaded('course')->name,
            'name' => $this->name,
            'groups' => EventGroupResource::collection($this->whenLoaded('sectionEventGroups')),
            'type' => $this->type,
            'date' => $this->date,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'category' => $this->category,
            'recurrence' => $this->recurrence,
            'description' => $this->description,
            'attachments' => $this->whenLoaded('attachments')->count() == 0 ?
                null : EventAttachmentsResource::makeJson($this),
        ];
    }
}
