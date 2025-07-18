<?php

namespace App\Http\Requests\QuestionBank;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class QuestionBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
        ];
    }

    // protected function onUpdate() {
    //     return [
    //     ];
    // }

    public function rules(): array
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else
        {
            return $this->onStore();
        }
        // else if (request()->isMethod('post'))
        // {
        //     return $this->onStore();
        // }
        // else
        // {
        //     return $this->onUpdate();
        // }
    }

    public function messages(): array
    {
        return [
            'course_id.required' => ValidationType::Required->getMessage(),
            'course_id.exists' => ValidationType::Exists->getMessage(),
            'page.required' => ValidationType::Required->getMessage(),
            'page.integer' => ValidationType::Integer->getMessage(),
            'page.gt' => ValidationType::GreaterThanZero->getMessage(),
            'page_size.integer' => ValidationType::Integer->getMessage(),
            'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'course_id' => FieldName::AssessmentId->getMessage(),
            'page' => FieldName::Page->getMessage(),
            'page_size' => FieldName::PageSize->getMessage(),
        ];
    }
}
