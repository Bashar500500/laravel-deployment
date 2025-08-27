<?php

namespace App\Http\Resources\Rubric;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricRubricCriteriasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'weight' => $this->weight,
            'description' => $this->description,
            'levels' => $this->levels,
        ];
    }
}
