<?php

namespace App\Http\Resources\QuestionBank;

use App\Http\Resources\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionResource;
use App\Http\Resources\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionResource;
use App\Http\Resources\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionResource;
use App\Http\Resources\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankQuestionsResource extends JsonResource
{
    public static function makeJson(
        QuestionBankResource $questionBankResource,
    ): array
    {
        return [
            'questionBankMultipleTypeQuestions' => QuestionBankMultipleTypeQuestionResource::collection($questionBankResource->whenLoaded('questionBankMultipleTypeQuestions')),
            'questionBankTrueOrFalseQuestions' => QuestionBankTrueOrFalseQuestionResource::collection($questionBankResource->whenLoaded('questionBankTrueOrFalseQuestions')),
            'questionBankShortAnswerQuestions' => QuestionBankShortAnswerQuestionResource::collection($questionBankResource->whenLoaded('questionBankShortAnswerQuestions')),
            'questionBankFillInBlankQuestions' => QuestionBankFillInBlankQuestionResource::collection($questionBankResource->whenLoaded('questionBankFillInBlankQuestions')),
        ];
    }
}
