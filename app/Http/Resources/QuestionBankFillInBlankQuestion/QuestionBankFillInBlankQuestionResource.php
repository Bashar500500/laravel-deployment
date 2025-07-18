<?php

namespace App\Http\Resources\QuestionBankFillInBlankQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankFillInBlankQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'questionBankId' => $this->question_bank_id,
            'text' => $this->text,
            'difficulty' => $this->difficulty,
            'category' => $this->category,
            'required' => $this->required == 0 ? 'false' : 'true',
            'displayOptions' => $this->display_options,
            'gradingOptions' => $this->grading_options,
            'settings' => $this->settings,
            'feedback' => $this->feedback,
            'blanks' => $this->whenLoaded('blanks'),
        ];
    }
}
