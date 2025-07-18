<?php

namespace App\Http\Resources\AssignmentSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentSubmitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignmentId' => $this->assignment_id,
            'studentId' => $this->student_id,
            'text' => $this->text,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'files' => $this->whenLoaded('attachments')->count() == 0 ? null : AssignmentAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
