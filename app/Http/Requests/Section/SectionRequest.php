<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Section\SectionStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class SectionRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['required', new Enum(SectionStatus::class)],
            'access' => ['required', 'array'],
            'access.release_date' => ['required', 'date', 'date_format:Y-m-d'],
            'access.has_prerequest' => ['sometimes', 'boolean'],
            'access.is_password_protected' => ['sometimes', 'boolean'],
            'access.password' => ['sometimes', 'string'],
            'groups' => ['sometimes', 'array'],
            'groups.*' => ['required_with:groups', 'exists:groups,id'],
            'resources' => ['sometimes', 'array'],
            'resources.files' => ['sometimes', 'array'],
            'resources.files.*' => ['required_with:resources.files', 'file'],
            'resources.links' => ['sometimes', 'array'],
            'resources.links.*' => ['required_with:resources.links', 'url'],
        ];
    }

    protected function onUpdate() {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(SectionStatus::class)],
            'access' => ['sometimes', 'array'],
            'access.release_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'access.has_prerequest' => ['sometimes', 'boolean'],
            'access.is_password_protected' => ['sometimes', 'boolean'],
            'access.password' => ['sometimes', 'string'],
            'groups' => ['sometimes', 'array'],
            'groups.*' => ['required_with:groups', 'exists:groups,id'],
            'resources' => ['sometimes', 'array'],
            'resources.files' => ['sometimes', 'array'],
            'resources.files.*' => ['required_with:resources.files', 'file'],
            'resources.links' => ['sometimes', 'array'],
            'resources.links.*' => ['required_with:resources.links', 'url'],
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
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'access.array' => ValidationType::Array->getMessage(),
    //         'access.release_date.date' => ValidationType::Date->getMessage(),
    //         'access.release_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'access.has_prerequest.required' => ValidationType::Required->getMessage(),
    //         'access.has_prerequest.boolean' => ValidationType::Boolean->getMessage(),
    //         'access.is_password_protected.boolean' => ValidationType::Boolean->getMessage(),
    //         'access.password.string' => ValidationType::String->getMessage(),
    //         'groups.array' => ValidationType::Array->getMessage(),
    //         'groups.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'groups.*.exists' => ValidationType::Exists->getMessage(),
    //         'resources.array' => ValidationType::Array->getMessage(),
    //         'resources.files.array' => ValidationType::Array->getMessage(),
    //         'resources.files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'resources.files.*.file' => ValidationType::File->getMessage(),
    //         'resources.links.array' => ValidationType::Array->getMessage(),
    //         'resources.links.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'resources.links.*.url' => ValidationType::Url->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'access' => FieldName::Access->getMessage(),
    //         'access.release_date' => FieldName::ReleaseDate->getMessage(),
    //         'access.has_prerequest' => FieldName::HasPrerequest->getMessage(),
    //         'access.is_password_protected' => FieldName::IsPasswordProtected->getMessage(),
    //         'access.password' => FieldName::Password->getMessage(),
    //         'groups' => FieldName::Groups->getMessage(),
    //         'groups.*' => FieldName::Groups->getMessage(),
    //         'resources' => FieldName::Resources->getMessage(),
    //         'resources.files' => FieldName::Files->getMessage(),
    //         'resources.files.*' => FieldName::Files->getMessage(),
    //         'resources.links' => FieldName::Links->getMessage(),
    //         'resources.links.*' => FieldName::Links->getMessage(),
    //     ];
    // }
}
