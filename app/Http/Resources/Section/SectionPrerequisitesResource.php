<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionPrerequisitesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'prerequisite' => class_basename($this->prerequisiteable_type) == 'Course' ?
                $this->prerequisiteable->name :
                $this->prerequisiteable->title,
            'condition' => $this->condition,
        ];
    }
}
