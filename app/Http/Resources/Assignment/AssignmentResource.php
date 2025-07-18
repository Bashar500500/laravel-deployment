<?php

namespace App\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'title' => $this->title,
            'status' => $this->status,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'dueDate' => $this->due_date,
            'points' => $this->points,
            'submissionSettings' => $this->submission_settings,
            'policies' => $this->policies,
            'stats' => AssignmentStatsResource::makeJson($this),
        ];
    }
}
