<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use App\Enums\LearningActivity\LearningActivityType;

class CalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'events' => CalendarEventsResource::collection(collect($this->events)
                ->filter(function ($event) {
                    return Carbon::parse($event->date)->isAfter(Carbon::today()) &&
                        Carbon::parse($event->date)->lessThanOrEqualTo(Carbon::today()->addMonth());
                })
            ),
            'learningActivities' => CalendarLearningActivitiesResource::collection(collect($this->learningActivities)
                ->filter(function ($activity) {
                    return $activity->type == LearningActivityType::LiveSession &&
                        Carbon::parse($activity->availability_start)->isAfter(Carbon::today()) &&
                        Carbon::parse($activity->availability_start)->lessThanOrEqualTo(Carbon::today()->addMonth());
                })
            ),
        ];
    }
}
