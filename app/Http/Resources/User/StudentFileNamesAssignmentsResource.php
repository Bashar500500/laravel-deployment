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
            'files' => $this->attachments?->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->attachments),
            'assignmentSubmits' => StudentFileNamesAssignmentAssignmentSubmitsResource::collection(collect($this->assignmentSubmits)
                ->filter(function ($submit) use ($studentId) {
                    return $submit->student_id == $studentId;
                })
            ),
        ];
    }
}
