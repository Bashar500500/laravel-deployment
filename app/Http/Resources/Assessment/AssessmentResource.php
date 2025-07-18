<?php

namespace App\Http\Resources\Assessment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'availableFrom' => $this->available_from,
            'availableTo' => $this->available_to,
            'attemptsAllowed' => $this->attempts_allowed,
            'shuffleQuestions' => $this->shuffle_questions == 0 ? 'false' : 'true',
            'feedbackOptions' => $this->feedback_options,
            'questions' => AssessmentQuestionsResource::makeJson($this),
            'stats' => AssessmentStatsResource::makeJson($this),
        ];
    }
}
