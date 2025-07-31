<?php

namespace App\Http\Resources\AssessmentSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentSubmitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessmentId' => $this->assessment_id,
            'studentId' => $this->student_id,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'detailedResults' => $this->detailed_results,
            'answers' => $this->answers,
            'files' => $this->whenLoaded('attachments')->count() == 0 ?
                null :
                AssessmentSubmitAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
