<?php

namespace App\Http\Resources\Assessment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionResource;
use App\Http\Resources\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionResource;
use App\Http\Resources\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionResource;
use App\Http\Resources\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionResource;

class AssessmentQuestionBankQuestionsResource extends JsonResource
{
    public static function makeJson(
        AssessmentResource $assessmentResource,
    ): array
    {
        return [
            'assessmentQuestionBankMultipleTypeQuestions' => QuestionBankMultipleTypeQuestionResource::collection($assessmentResource->whenLoaded('assessmentQuestionBankMultipleTypeQuestions')),
            'assessmentQuestionBankTrueOrFalseQuestions' => QuestionBankTrueOrFalseQuestionResource::collection($assessmentResource->whenLoaded('assessmentQuestionBankTrueOrFalseQuestions')),
            'assessmentQuestionBankShortAnswerQuestions' => QuestionBankShortAnswerQuestionResource::collection($assessmentResource->whenLoaded('assessmentQuestionBankShortAnswerQuestions')),
            'assessmentQuestionBankFillInBlankQuestions' => QuestionBankFillInBlankQuestionResource::collection($assessmentResource->whenLoaded('assessmentQuestionBankFillInBlankQuestions')),
        ];
    }
}
