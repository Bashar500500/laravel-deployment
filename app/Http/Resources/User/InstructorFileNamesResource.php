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
            'sections' => FileNamesSectionsResource::collection(collect($this->sections)),
            'events' => FileNamesEventsResource::collection(collect($this->events)),
            'assignments' => InstructorFileNamesAssignmentsResource::collection(collect($this->assignments)),
            'projects' => FileNamesProjectsResource::collection(collect($this->projects)),
            'wikis' => FileNamesWikisResource::collection(collect($this->wikis)),
        ];
    }
}
