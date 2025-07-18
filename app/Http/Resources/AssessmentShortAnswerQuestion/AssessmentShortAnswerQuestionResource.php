<?php

namespace App\Http\Resources\AssessmentShortAnswerQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentShortAnswerQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessmentId' => $this->assessment_id,
            'text' => $this->text,
            'points' => $this->points,
            'difficulty' => $this->difficulty,
            'category' => $this->category,
            'required' => $this->required == 0 ? 'false' : 'true',
            'answerType' => $this->answer_type,
            'characterLimit' => $this->character_limit,
            'acceptedAnswers' => $this->accepted_answers,
            'settings' => $this->settings,
            'feedback' => $this->feedback,
        ];
    }
}
