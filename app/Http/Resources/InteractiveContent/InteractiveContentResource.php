<?php

namespace App\Http\Resources\InteractiveContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractiveContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'file' => $this->whenLoaded('attachment') ?
                $this->whenLoaded('attachment')->url :
                null,
        ];
    }
}
