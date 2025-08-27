<?php

namespace App\Http\Requests\Plagiarism;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class PlagiarismRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'assignment_submit_id' => ['required', 'exists:assignment_submits,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onUpdate() {
        return [
            'score' => ['required', 'integer', 'gt:0'],
        ];
    }

    public function rules(): array
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else
        {
            return $this->onUpdate();
        }
    }

    // public function messages(): array
    // {
    //     return [
    //         'assignment_submit_id.required' => ValidationType::Required->getMessage(),
    //         'assignment_submit_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'score.required' => ValidationType::Required->getMessage(),
    //         'score.integer' => ValidationType::Integer->getMessage(),
    //         'score.gt' => ValidationType::GreaterThanZero->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assignment_submit_id' => FieldName::AssignmentSubmitId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'score' => FieldName::Score->getMessage(),
    //     ];
    // }
}
