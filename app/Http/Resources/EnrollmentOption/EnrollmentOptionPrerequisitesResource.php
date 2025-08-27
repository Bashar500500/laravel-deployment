<?php

namespace App\Http\Resources\EnrollmentOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentOptionPrerequisitesResource extends JsonResource
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
