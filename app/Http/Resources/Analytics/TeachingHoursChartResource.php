<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachingHoursChartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'series' => $this->prepareSeriesData(),
            'categories' => ["S", "M", "T", "W", "T", "F", "S"],
            'period' => 'week',
        ];
    }

    private function prepareSeriesData(): array
    {
        $data = [];
        $data[0]['name'] = 'name';
        $data[0]['data'] = [$this->teachingHours->total_hours];
        $data[1]['name'] = 'total-actual';
        $data[1]['data'] = [$this->teachingHours->completed_hours];

        return $data;
    }
}
