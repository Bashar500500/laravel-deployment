<?php

namespace App\Http\Resources\Rule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'points' => $this->points,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'metadata' => RuleMetadataResource::makeJson($this),
        ];
    }
}
