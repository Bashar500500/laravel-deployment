<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ProjectSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'project_id.required' => ValidationType::Required->getMessage(),
    //         'project_id.exists' => ValidationType::Exists->getMessage(),
    //         'files.required' => ValidationType::Required->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required' => ValidationType::Required->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'project_id' => FieldName::ProjectId->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
