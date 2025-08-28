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
            'events' => CalendarEventsResource::collection($this->events
                ->whereDate('date', '>', Carbon::today())
                ->whereDate('date', '<=', Carbon::today()->addMonth())
            ),
            'eventStudents' => CalendarStudentsResource::collection($this->students),
            'learningActivities' => CalendarLearningActivitiesResource::collection($this->learningActivities
                ->where('type', LearningActivityType::LiveSession)
                ->whereDate('availability_start', '>', Carbon::today())
                ->whereDate('availability_start', '<=', Carbon::today()->addMonth())
            ),
        ];
    }
}
