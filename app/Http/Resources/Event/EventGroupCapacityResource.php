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
            'min' => $eventGroupResource->capacity_min,
            'max' => $eventGroupResource->capacity_max,
            'current' => $eventGroupResource->whenLoaded('students')->count(),
        ];
    }
}
