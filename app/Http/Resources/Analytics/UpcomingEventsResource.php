<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UpcomingEventsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $event = $this->events()
                ->where('date', '>', Carbon::today())
                ->orderBy('date')
                ->first();
        return [
            'id' => $event->id,
            'title' => $event->name,
            'date' => $event->date,
            'type' => $event->type,
        ];
    }
}
