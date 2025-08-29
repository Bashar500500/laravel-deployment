<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorFileNamesAssignmentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'files' => $this->attachments?->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->attachments),
            'assignmentSubmits' => InstructorFileNamesAssignmentAssignmentSubmitsResource::collection(collect($this->assignmentSubmits)),
        ];
    }
}
