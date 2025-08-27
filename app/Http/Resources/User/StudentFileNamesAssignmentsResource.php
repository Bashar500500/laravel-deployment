<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFileNamesAssignmentsResource extends JsonResource
{
    protected $studentId;

    public function __construct($resource, $studentId)
    {
        parent::__construct($resource);
        $this->studentId = $studentId;
    }

    public function toArray(Request $request): array
    {
        $studentId = $this->studentId;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'files' => $this->load('attachments')->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->load('attachments')),
            'assignmentSubmits' => StudentFileNamesAssignmentAssignmentSubmitsResource::collection(
                $this->load('assignmentSubmits')->where('student_id', $studentId)),
        ];
    }
}
