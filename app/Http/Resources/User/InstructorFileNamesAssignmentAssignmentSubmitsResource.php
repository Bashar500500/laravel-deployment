<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class InstructorFileNamesAssignmentAssignmentSubmitsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'studentName' => $this->load('student') ?
                $this->load('student')->first_name . $this->load('student')->last_name :
                null,
            'instructorFiles' => $this->load('attachments')
                ->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->load('attachments')
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitInstructorFiles)),
            'studentFiles' => $this->load('attachments')
                ->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->load('attachments')
                    ->where('reference_field', AttachmentReferenceField::AssignmentSubmitStudentFiles)),
        ];
    }
}
