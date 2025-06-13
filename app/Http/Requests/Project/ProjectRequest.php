<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ProjectRequest extends FormRequest
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
            'leader_id' => ['required', 'exists:users,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'name' => ['required', 'string'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'description' => ['required', 'string'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'start_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required_with:start_date', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'description' => ['sometimes', 'string'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
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
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'leader_id.required' => ValidationType::Required->getMessage(),
    //         'leader_id.exists' => ValidationType::Exists->getMessage(),
    //         'group_id.required' => ValidationType::Required->getMessage(),
    //         'group_id.exists' => ValidationType::Exists->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'start_date.required' => ValidationType::Required->getMessage(),
    //         'start_date.date' => ValidationType::Date->getMessage(),
    //         'start_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.required' => ValidationType::Required->getMessage(),
    //         'end_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'end_date.date' => ValidationType::Date->getMessage(),
    //         'end_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'leader_id' => FieldName::LeaderId->getMessage(),
    //         'group_id' => FieldName::GroupId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'start_date' => FieldName::StartDate->getMessage(),
    //         'end_date' => FieldName::EndDate->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
