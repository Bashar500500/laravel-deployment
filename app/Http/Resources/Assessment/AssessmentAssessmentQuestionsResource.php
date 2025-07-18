<?php

namespace App\Http\Resources\Assessment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionResource;
use App\Http\Resources\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionResource;
use App\Http\Resources\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionResource;
use App\Http\Resources\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionResource;

class AssessmentAssessmentQuestionsResource extends JsonResource
{
    public static function makeJson(
        AssessmentResource $assessmentResource,
    ): array
    {
        return [
            'assessmentMultipleTypeQuestions' => AssessmentMultipleTypeQuestionResource::collection($assessmentResource->whenLoaded('assessmentMultipleTypeQuestions')),
            'assessmentTrueOrFalseQuestions' => AssessmentTrueOrFalseQuestionResource::collection($assessmentResource->whenLoaded('assessmentTrueOrFalseQuestions')),
            'assessmentShortAnswerQuestions' => AssessmentShortAnswerQuestionResource::collection($assessmentResource->whenLoaded('assessmentShortAnswerQuestions')),
            'assessmentFillInBlankQuestions' => AssessmentFillInBlankQuestionResource::collection($assessmentResource->whenLoaded('assessmentFillInBlankQuestions')),
        ];
    }
}
