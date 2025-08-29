<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class StudentFileNamesAssignmentAssignmentSubmitsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorFiles' => $this->attachments?->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->attachments
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)),
            'studentFiles' => $this->attachments?->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->attachments
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)),
        ];
    }
}
