<?php

namespace App\Http\Resources\Prerequisite;

use App\Models\Course\Course;
use App\Models\Section\Section;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrerequisiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $prerequisiteableAttribute = class_basename($this->prerequisiteable_type) == 'Course' ?
            Course::find($this->prerequisiteable_id) :
            Section::find($this->prerequisiteable_id);
        $requiredableAttribute = class_basename($this->requiredable_type) == 'Course' ?
            Course::find($this->requiredable_id) :
            Section::find($this->requiredable_id);

        return [
            'id' => $this->id,
            'type' => $this->type,
            'prerequisite' => $prerequisiteableAttribute,
            'requiredFor' => $requiredableAttribute,
            'appliesTo' => $this->applies_to,
            'condition' => $this->condition,
            'allowOverride' => $this->allow_override == 0 ? 'false' : 'true',
        ];
    }
}
