<?php

namespace App\Http\Requests\Whiteboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class WhiteboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'course_id' => ['sometimes', 'exists:courses,id'],
            'name' => ['sometimes', 'string'],
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
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //     ];
    // }
}
