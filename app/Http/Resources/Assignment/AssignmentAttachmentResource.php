<?php

namespace App\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
        ];
    }
}
