<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseCompletionRatesAndstudentEngagementResource extends JsonResource
{
    protected $courseId;

    public function __construct($resource, $courseId)
    {
        parent::__construct($resource);
        $this->courseId = $courseId;
    }

    public function toArray(Request $request): array
    {
        $courseId = $this->courseId;
        return [
            'courseCompletionRates' =>
                CourseCompletionRatesAndstudentEngagementCourseCompletionRatesResource::collection(
                    $this->courses
                        ->where('id', $courseId)
                        ->first()
                        ->progresses
                        ->sortBy(function ($progress) {
                            return Carbon::parse($progress->created_at)->year;
                        }),
                ),
            'studentEngagement' =>
                CourseCompletionRatesAndstudentEngagementStudentEngagementResource::collection(
                    $this->courses
                        ->where('id', $courseId)
                        ->first()
                        ->assessments->flatMap->submits
                        ->sortBy(function ($submit) {
                            return Carbon::parse($submit->created_at)->year;
                        }),
                ),
        ];
    }
}
