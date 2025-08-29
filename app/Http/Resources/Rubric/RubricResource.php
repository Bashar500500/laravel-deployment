<?php

namespace App\Http\Resources\Rubric;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                $this->whenLoaded('instructor')->last_name,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'maxPoints' => $this->whenLoaded('rubricCriterias')->sum('weight'),
            'used' => $this->whenLoaded('assignments')->count(),
            'averageScore' => $this->averageScore(),
            'rubricCriterias' => RubricRubricCriteriasResource::collection($this->whenLoaded('rubricCriterias')),
        ];
    }
}
