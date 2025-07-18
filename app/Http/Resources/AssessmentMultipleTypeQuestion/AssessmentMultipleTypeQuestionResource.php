<?php

namespace App\Http\Resources\AssessmentMultipleTypeQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentMultipleTypeQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessmentId' => $this->assessment_id,
            'type' => $this->type,
            'text' => $this->text,
            'points' => $this->points,
            'difficulty' => $this->difficulty,
            'category' => $this->category,
            'required' => $this->required == 0 ? 'false' : 'true',
            'additionalSettings' => $this->additional_settings,
            'settings' => $this->settings,
            'feedback' => $this->feedback,
            'options' => $this->whenLoaded('options'),
        ];
    }
}
