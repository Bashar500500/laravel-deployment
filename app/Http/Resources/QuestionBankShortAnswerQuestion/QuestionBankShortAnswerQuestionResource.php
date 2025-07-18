<?php

namespace App\Http\Resources\QuestionBankShortAnswerQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankShortAnswerQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'questionBankId' => $this->question_bank_id,
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
