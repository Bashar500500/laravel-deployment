<?php

namespace App\Http\Resources\CourseReview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'studentId' => $this->student_id,
            'courseId' => $this->course_id,
            'rating' => $this->rating,
            'wouldRecommend' => $this->would_recommend == 0 ? 'false' : 'true',
        ];
    }
}
