<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class FileNamesProjectProjectSubmitsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorFiles' => $this->attachments?->where('reference_field', AttachmentReferenceField::ProjectSubmitInstructorFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->attachments
                    ->where('reference_field', AttachmentReferenceField::ProjectSubmitInstructorFiles)),
            'studentFiles' => $this->attachments?->where('reference_field', AttachmentReferenceField::ProjectSubmitStudentFiles)
                ->count() == 0 ? null :
                FileNamesAttachmentResource::collection($this->attachments
                    ->where('reference_field', AttachmentReferenceField::ProjectSubmitStudentFiles)),
        ];
    }
}
