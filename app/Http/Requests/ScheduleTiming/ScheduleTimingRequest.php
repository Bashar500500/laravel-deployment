<?php

namespace App\Http\Requests\ScheduleTiming;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ScheduleTimingRequest extends FormRequest
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
            'instructor_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'instructor_available_timings' => ['required', 'array'],
            'instructor_available_timings.*' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'instructor_available_timings' => ['sometimes', 'array'],
            'instructor_available_timings.*' => ['required_with:instructor_available_timings', 'string'],
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
    //         'instructor_id.required' => ValidationType::Required->getMessage(),
    //         'instructor_id.exists' => ValidationType::Exists->getMessage(),
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'instructor_available_timings.required' => ValidationType::Required->getMessage(),
    //         'instructor_available_timings.array' => ValidationType::Array->getMessage(),
    //         'instructor_available_timings.*.required' => ValidationType::Required->getMessage(),
    //         'instructor_available_timings.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'instructor_available_timings.*.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'instructor_id' => FieldName::InstructorId->getMessage(),
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'instructor_available_timings' => FieldName::InstructorAvailableTimings->getMessage(),
    //         'instructor_available_timings.*' => FieldName::InstructorAvailableTimings->getMessage(),
    //     ];
    // }
}
