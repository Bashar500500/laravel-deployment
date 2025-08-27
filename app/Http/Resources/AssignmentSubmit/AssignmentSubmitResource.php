<?php

namespace App\Http\Resources\AssignmentSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class AssignmentSubmitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment' => AssignmentSubmitAssignmentResource::make($this->whenLoaded('assignment')),
            'studentId' => $this->student_id,
            'studentName' => $this->whenLoaded('student')->first_name .
                ' ' . $this->whenLoaded('student')->last_name,
            'studentEmail' => $this->whenLoaded('student')->email,
            'groups' => AssignmentSubmitGroupsResource::collection($this->whenLoaded('student')->groups),
            'status' => $this->status,
            'text' => $this->text,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'instructorFiles' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)
                ->count() == 0 ? null :
                AssignmentAttachmentResource::collection($this->whenLoaded('attachments')
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)),
            'studentFiles' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)
                ->count() == 0 ? null :
                AssignmentAttachmentResource::collection($this->whenLoaded('attachments')
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)),
            'createdAt' => $this->created_at,
        ];
    }
}
