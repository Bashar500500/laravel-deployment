<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsTeachingHoursResource extends JsonResource
{
    public static function makeJson(
        StatisticsResource $statisticsResource,
    ): array
    {
        return [
            'total' => $statisticsResource->teachingHours->total_hours,
            'upcoming' => $statisticsResource->teachingHours->upcoming,
            'completed' => $statisticsResource->teachingHours->completed_hours,
            'break' => $statisticsResource->teachingHours->break,
        ];
    }
}
