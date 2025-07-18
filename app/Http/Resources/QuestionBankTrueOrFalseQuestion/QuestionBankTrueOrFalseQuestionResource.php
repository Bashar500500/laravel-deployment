<?php

namespace App\Http\Resources\QuestionBankTrueOrFalseQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankTrueOrFalseQuestionResource extends JsonResource
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
            'correctAnswer' => $this->correct_answer,
            'labels' => $this->labels,
            'settings' => $this->settings,
            'feedback' => $this->feedback,
            'options' => $this->whenLoaded('options'),
        ];
    }
}
