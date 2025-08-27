<?php

namespace App\Http\Resources\Plagiarism;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlagiarismResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'studentId' => $this->whenLoaded('assignmentSubmit')->student_id,
            'studentName' => $this->whenLoaded('assignmentSubmit')->student->first_name .
                ' ' . $this->whenLoaded('assignmentSubmit')->student->last_name,
            'assignment' => $this->whenLoaded('assignmentSubmit')->assignment->title,
            'submissionDate' => $this->whenLoaded('assignmentSubmit')->created_at,
            'score' => $this->score,
            'status' => $this->status,
        ];
    }
}
