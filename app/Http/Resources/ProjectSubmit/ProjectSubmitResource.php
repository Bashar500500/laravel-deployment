<?php

namespace App\Http\Resources\ProjectSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class ProjectSubmitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'projectId' => $this->project_id,
            'project' => ProjectSubmitProjectResource::make($this->whenLoaded('assignment')),
            'status' => $this->status,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'instructorFiles' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::ProjectSubmitInstructorFiles)
                ->count() == 0 ? null :
                ProjectAttachmentResource::collection($this->whenLoaded('attachments')
                    ->where('reference_field', AttachmentReferenceField::ProjectSubmitInstructorFiles)),
            'studentFiles' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::ProjectSubmitStudentFiles)
                ->count() == 0 ? null :
                ProjectAttachmentResource::collection($this->whenLoaded('attachments')
                    ->where('reference_field', AttachmentReferenceField::ProjectSubmitStudentFiles)),
            'createdAt' => $this->created_at,
        ];
    }
}
