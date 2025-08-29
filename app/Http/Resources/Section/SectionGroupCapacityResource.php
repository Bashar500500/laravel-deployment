<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionGroupCapacityResource extends JsonResource
{
    public static function makeJson(
        SectionGroupResource $sectionGroupResource
    ): array
    {
        return [
            'min' => $sectionGroupResource->group->capacity_min,
            'max' => $sectionGroupResource->group->capacity_max,
            'current' => $sectionGroupResource->group->students?->count(),
        ];
    }
}
