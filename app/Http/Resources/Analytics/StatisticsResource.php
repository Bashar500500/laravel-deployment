<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'teachingHours' => StatisticsTeachingHoursResource::makeJson($this),
            'clockStatus' => $this->first_name . $this->last_name,
        ];
    }
}
