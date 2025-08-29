<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'date' => $this->date,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'category' => $this->category,
            'recurrence' => $this->recurrence,
            'description' => $this->description,
            'groups' => CalendarGroupsResource::collection(collect($this->sectionEventGroups)),
            'attachments' => $this->attachments?->count() == 0 ?
                null : CalendarEventsAttachmentsResource::makeJson($this),
        ];
    }
}
