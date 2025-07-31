<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseCompletionRatesAndstudentEngagementStudentEngagementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'year' => Carbon::parse($this[0]->created_at)->year,
            'activeParticipation' => $this[0]->assessment->course->students->count(),
            'assessmentPerformance' => $this->sum('score') / $this->count(),
        ];
    }
}
