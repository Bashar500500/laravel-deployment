<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Question\QuestionCorrectAnswer;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'category' => ['sometimes', 'string'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'category' => ['required', 'string'],
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', new Enum(QuestionCorrectAnswer::class)],
            'code_snippets' => ['required', 'string'],
            'answer_explanation' => ['required', 'string'],
            'question_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'video_link' => ['required', 'url'],
        ];
    }

    protected function onUpdate() {
        return [
            'category' => ['sometimes', 'string'],
            'question' => ['sometimes', 'string'],
            'option_a' => ['sometimes', 'string'],
            'option_b' => ['sometimes', 'string'],
            'option_c' => ['sometimes', 'string'],
            'option_d' => ['sometimes', 'string'],
            'correct_answer' => ['sometimes', new Enum(QuestionCorrectAnswer::class)],
            'code_snippets' => ['sometimes', 'string'],
            'answer_explanation' => ['sometimes', 'string'],
            'question_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'video_link' => ['sometimes', 'url'],
        ];
    }

    public function rules(): array
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else if (request()->isMethod('post'))
        {
            return $this->onStore();
        }
        else
        {
            return $this->onUpdate();
        }
    }

    // public function messages(): array
    // {
    //     return [
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'category.string' => ValidationType::String->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'question.required' => ValidationType::Required->getMessage(),
    //         'question.string' => ValidationType::String->getMessage(),
    //         'option_a.required' => ValidationType::Required->getMessage(),
    //         'option_a.string' => ValidationType::String->getMessage(),
    //         'option_b.required' => ValidationType::Required->getMessage(),
    //         'option_b.string' => ValidationType::String->getMessage(),
    //         'option_c.required' => ValidationType::Required->getMessage(),
    //         'option_c.string' => ValidationType::String->getMessage(),
    //         'option_d.required' => ValidationType::Required->getMessage(),
    //         'option_d.string' => ValidationType::String->getMessage(),
    //         'correct_answer.required' => ValidationType::Required->getMessage(),
    //         'correct_answer.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'code_snippets.required' => ValidationType::Required->getMessage(),
    //         'code_snippets.string' => ValidationType::String->getMessage(),
    //         'answer_explanation.required' => ValidationType::Required->getMessage(),
    //         'answer_explanation.string' => ValidationType::String->getMessage(),
    //         'question_image.image' => ValidationType::Image->getMessage(),
    //         'question_image.mimes' => ValidationType::ImageMimes->getMessage(),
    //         'video_link.required' => ValidationType::Required->getMessage(),
    //         'video_link.url' => ValidationType::Url->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'question' => FieldName::Question->getMessage(),
    //         'option_a' => FieldName::OptionA->getMessage(),
    //         'option_b' => FieldName::OptionB->getMessage(),
    //         'option_c' => FieldName::OptionC->getMessage(),
    //         'option_d' => FieldName::OptionD->getMessage(),
    //         'correct_answer' => FieldName::CorrectAnswer->getMessage(),
    //         'code_snippets' => FieldName::CodeSnippets->getMessage(),
    //         'answer_explanation' => FieldName::AnswerExplanation->getMessage(),
    //         'question_image' => FieldName::QuestionImage->getMessage(),
    //         'video_link' => FieldName::VideoLink->getMessage(),
    //     ];
    // }
}
