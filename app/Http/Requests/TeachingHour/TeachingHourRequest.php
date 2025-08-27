<?php

namespace App\Http\Requests\TeachingHour;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TeachingHour\TeachingHourStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class TeachingHourRequest extends FormRequest
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
            'total_hours' => ['required', 'integer', 'gt:0'],
            'upcoming' => ['required', 'integer', 'gte:0'],
            'status' => ['required', new Enum(TeachingHourStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'total_hours' => ['sometimes', 'integer', 'gt:0'],
            'upcoming' => ['sometimes', 'integer', 'gte:0'],
            'status' => ['sometimes', new Enum(TeachingHourStatus::class)],
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
    //         'total_hours.required' => ValidationType::Required->getMessage(),
    //         'total_hours.integer' => ValidationType::Integer->getMessage(),
    //         'total_hours.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'completed_hours.required' => ValidationType::Required->getMessage(),
    //         'completed_hours.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'completed_hours.integer' => ValidationType::Integer->getMessage(),
    //         'completed_hours.lte' => ValidationType::LessThanOrEqual->getMessage(),
    //         'upcoming.required' => ValidationType::Required->getMessage(),
    //         'upcoming.integer' => ValidationType::Integer->getMessage(),
    //         'upcoming.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'break.required' => ValidationType::Required->getMessage(),
    //         'break.integer' => ValidationType::Integer->getMessage(),
    //         'break.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'instructor_id' => FieldName::InstructorId->getMessage(),
    //         'total_hours' => FieldName::TotalHours->getMessage(),
    //         'completed_hours' => FieldName::CompletedHours->getMessage(),
    //         'upcoming' => FieldName::Upcoming->getMessage(),
    //         'break' => FieldName::Break->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //     ];
    // }
}
