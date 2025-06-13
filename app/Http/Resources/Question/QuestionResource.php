<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;
use Illuminate\Support\Facades\Storage;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'category' => $this->category,
            'question' => $this->question,
            'optionA' => $this->option_a,
            'optionB' => $this->option_b,
            'optionC' => $this->option_c,
            'optionD' => $this->option_d,
            'correctAnswer' => $this->correct_answer,
            'codeSnippets' => $this->code_snippets,
            'answerExplanation' => $this->answer_explanation,
            // 'question_image' => $this->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::QuestionImage)->count() == 0 ? null : $this->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::QuestionImage)[0]->url,
            'question_image' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::QuestionImage)->count() == 0 ?
                null :
                $this->prepareAttachmentData(
                    $this->id,
                    $this->whenLoaded('attachments')
                    ->where('reference_field', AttachmentReferenceField::QuestionImage)[0]->url),
            'videoLink' => $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::QuestionVideoLink)->count() == 0 ?
                null :
                $this->whenLoaded('attachments')
                ->where('reference_field', AttachmentReferenceField::QuestionVideoLink)[0]->url,
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Category/' . $id . '/Images/' . $url);
        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);
        return 'data:' . $metadata . ';base64,' . $data;
    }
}
