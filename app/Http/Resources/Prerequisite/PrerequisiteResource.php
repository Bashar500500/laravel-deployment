<?php

namespace App\Http\Resources\Prerequisite;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrerequisiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'prerequisite' => class_basename($this->prerequisiteable_type) == 'Course' ?
                $this->prerequisiteable->name :
                $this->prerequisiteable->title,
            'requiredFor' => class_basename($this->requiredable_type) == 'Course' ?
                $this->requiredable->name :
                $this->requiredable->title,
            'appliesTo' => $this->applies_to,
            'condition' => $this->condition,
            'allowOverride' => $this->allow_override == 0 ? 'false' : 'true',
        ];
    }
}
