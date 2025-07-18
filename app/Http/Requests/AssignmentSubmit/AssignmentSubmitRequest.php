<?php

namespace App\Http\Requests\AssignmentSubmit;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssignmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'assignment_id' => ['required', 'exists:assignments,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onUpdate() {
        return [
            'score' => ['required', 'integer', 'gt:0'],
            'feedback' => ['required', 'string'],
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
    //         'assignment_id.required' => ValidationType::Required->getMessage(),
    //         'assignment_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'score.required' => ValidationType::Required->getMessage(),
    //         'score.integer' => ValidationType::Integer->getMessage(),
    //         'score.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'feedback.required' => ValidationType::Required->getMessage(),
    //         'feedback.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assignment_id' => FieldName::AssignmentId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'score' => FieldName::Score->getMessage(),
    //         'feedback' => FieldName::Feedback->getMessage(),
    //     ];
    // }
}
