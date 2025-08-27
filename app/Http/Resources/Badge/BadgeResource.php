<?php

namespace App\Http\Resources\Badge;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'subCategory' => $this->sub_category,
            'difficulty' => $this->difficulty,
            'icon' => $this->icon,
            'color' => $this->color,
            'shape' => $this->shape,
            'imageUrl' => $this->image_url,
            'status' => $this->status,
            'reward' => $this->reward,
            'metadata' => BadgeMetadataResource::makeJson($this),
        ];
    }
}
