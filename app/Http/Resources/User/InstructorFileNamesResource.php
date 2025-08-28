<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorFileNamesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sections' => FileNamesSectionsResource::collection($this->sections),
            'events' => FileNamesEventsResource::collection($this->events),
            'assignments' => InstructorFileNamesAssignmentsResource::collection($this->assignments),
            'projects' => FileNamesProjectsResource::collection($this->projects),
            'wikis' => FileNamesWikisResource::collection($this->wikis),
        ];
    }
}
