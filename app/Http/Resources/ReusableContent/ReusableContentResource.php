<?php

namespace App\Http\Resources\ReusableContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReusableContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'tags' => $this->tags,
            'shareWithCommunity' => $this->share_with_community == 0 ? 'false' : 'true',
            'file' => $this->whenLoaded('attachment') ?
                $this->whenLoaded('attachment')->url :
                null,
        ];
    }
}
