<?php

namespace App\Http\Resources\Grade;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'assignmentName' => $this->whenLoaded('assignment')->name,
            'assignmentName' => 'assignment name',
            'studentName' => $this->whenLoaded('student')->first_name .
                $this->whenLoaded('student')->last_name,
            'dueDate' => $this->due_date,
            'extendedDueDate' => $this->extended_due_date,
            'status' => $this->status,
            'pointsEarned' => $this->points_earned,
            'maxPoints' => $this->max_points,
            'percentage' => $this->percentage,
            'category' => $this->category,
            'classAverage' => $this->class_average,
            'trend' => $this->trend,
            'trendData' => $this->trend_data,
            'feedback' => $this->feedback,
            'resubmission' => $this->resubmission,
            'resubmissionDue' => $this->resubmission_due,
        ];
    }
}
