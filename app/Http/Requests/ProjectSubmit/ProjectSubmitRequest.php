<?php

namespace App\Http\Requests\ProjectSubmit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProjectSubmit\ProjectSubmitLevel;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ProjectSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onUpdate() {
        return [
            'rubric_criterias' => ['required', 'array'],
            'rubric_criterias.*.rubric_criteria_id' => ['required', 'exists:rubric_criterias,id'],
            'rubric_criterias.*.level' => ['required', new Enum(ProjectSubmitLevel::class)],
            'feedback' => ['required', 'string'],
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
        else
        {
            return $this->onUpdate();
        }
    }

    // public function messages(): array
    // {
    //     return [
    //         'project_id.required' => ValidationType::Required->getMessage(),
    //         'project_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'rubric_criterias.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.rubric_criteria_id.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.rubric_criteria_id.exists' => ValidationType::Exists->getMessage(),
    //         'rubric_criterias.*.level.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.level.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'feedback.required' => ValidationType::Required->getMessage(),
    //         'feedback.string' => ValidationType::String->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'project_id' => FieldName::ProjectId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'rubric_criterias' => FieldName::RubricCriterias->getMessage(),
    //         'rubric_criterias.*.rubric_criteria_id' => FieldName::RubricCriteriaId->getMessage(),
    //         'rubric_criterias.*.level' => FieldName::Level->getMessage(),
    //         'feedback' => FieldName::Feedback->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
