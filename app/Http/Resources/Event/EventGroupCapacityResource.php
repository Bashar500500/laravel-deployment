<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class EventGroupCapacityResource extends JsonResource
{
    public static function makeJson(
        EventGroupResource $eventGroupResource
    ): array
    {
        return [
            'min' => $eventGroupResource->group->capacity_min,
            'max' => $eventGroupResource->group->capacity_max,
            'current' => $eventGroupResource->group->students?->count(),
        ];
    }
}
