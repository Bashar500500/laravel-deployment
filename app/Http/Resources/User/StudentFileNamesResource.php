<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFileNamesResource extends JsonResource
{
    protected $studentId;

    public function __construct($resource, $studentId)
    {
        parent::__construct($resource);
        $this->studentId = $studentId;
    }

    public function toArray(Request $request): array
    {
        $studentId = $this->studentId;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sections' => FileNamesSectionsResource::collection(collect($this->sections)),
            'events' => FileNamesEventsResource::collection(collect($this->events)),
            'assignments' => StudentFileNamesAssignmentsResource::collection(collect($this->assignments)),
            'projects' => $this->studentProjects($studentId)
                ? $this->studentProjects($studentId)->map(
                    fn($project) => new FileNamesProjectsResource($project, $studentId)
                )
                : [],
            'wikis' => FileNamesWikisResource::collection(collect($this->wikis)),
        ];
    }
}
