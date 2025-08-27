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
            'sections' => FileNamesSectionsResource::collection($this->load('sections')),
            'events' => FileNamesEventsResource::collection($this->load('events')),
            'assignments' => InstructorFileNamesAssignmentsResource::collection($this->load('assignments')),
            'projects' => FileNamesProjectsResource::collection($this->load('projects')),
            'wikis' => FileNamesWikisResource::collection($this->load('wikis')),
        ];
    }
}
