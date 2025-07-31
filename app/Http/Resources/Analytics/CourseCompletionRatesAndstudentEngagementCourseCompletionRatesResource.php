<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseCompletionRatesAndstudentEngagementCourseCompletionRatesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'year' => Carbon::parse($this[0]->created_at)->year,
            'completionRate' => $this->where('progress', 100)->sum('progress') /
                $this->course->students->count(),
            'avgProgress' => $this->sum('progress') / $this->count(),
        ];
    }
}
