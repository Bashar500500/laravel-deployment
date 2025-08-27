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
            'files' => $this->load('attachments')->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->load('attachments')),
            'assignmentSubmits' => InstructorFileNamesAssignmentAssignmentSubmitsResource::collection($this->load('assignmentSubmits')),
        ];
    }
}
