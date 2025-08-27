<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'learning_activity_id' => ['required', 'exists:learning_activities,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'learning_activity_id' => ['required', 'exists:learning_activities,id'],
            'student_id' => ['required', 'exists:users,id'],
            'is_present' => ['required', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'is_present' => ['sometimes', 'boolean'],
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
    //         'learning_activity_id.required' => ValidationType::Required->getMessage(),
    //         'learning_activity_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'student_id.required' => ValidationType::Required->getMessage(),
    //         'student_id.exists' => ValidationType::Exists->getMessage(),
    //         'is_present.required' => ValidationType::Required->getMessage(),
    //         'is_present.boolean' => ValidationType::Boolean->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'learning_activity_id' => FieldName::LearningActivityId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'student_id' => FieldName::StudentId->getMessage(),
    //         'is_present' => FieldName::IsPresent->getMessage(),
    //     ];
    // }
}
