<?php

namespace App\Http\Resources\QuestionBank;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'questions' => QuestionBankQuestionsResource::makeJson($this),
        ];
    }
}
