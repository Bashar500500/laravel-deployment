<?php

namespace App\Http\Resources\Assessment;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentQuestionsResource extends JsonResource
{
    public static function makeJson(
        AssessmentResource $assessmentResource,
    ): array
    {
        return [
            'assessmentQuestions' => AssessmentAssessmentQuestionsResource::makeJson($assessmentResource),
            'questionBankQuestions' => AssessmentQuestionBankQuestionsResource::makeJson($assessmentResource),
        ];
    }
}
