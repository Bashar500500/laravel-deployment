<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupCapacityResource extends JsonResource
{
    public static function makeJson(
        GroupResource $groupResource
    ): array
    {
        return [
            'min' => $groupResource->capacity_min,
            'max' => $groupResource->capacity_max,
            'current' => $groupResource->whenLoaded('students')->count(),
        ];
    }
}
