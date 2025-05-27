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
            'min' => $sectionGroupResource->capacity_min,
            'max' => $sectionGroupResource->capacity_max,
            'current' => $sectionGroupResource->whenLoaded('students')->count(),
        ];
    }
}
