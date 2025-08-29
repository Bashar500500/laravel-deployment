<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupSectionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->groupable_id,
        ];
    }
}
