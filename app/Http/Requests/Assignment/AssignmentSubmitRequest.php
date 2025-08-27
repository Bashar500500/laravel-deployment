<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Assignment\AssignmentSubmitType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssignmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignment_id' => ['required', 'exists:assignments,id'],
            'type' => ['required', new Enum(AssignmentSubmitType::class)],
            'files' => ['required_if:type,==,File Upload', 'missing_if:type,==,Text Entry', 'array'],
            'files.*' => ['required_with:files', 'file'],
            'text' => ['required_if:type,==,Text Entry', 'missing_if:type,==,File Upload', 'string'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'assignment_id.required' => ValidationType::Required->getMessage(),
    //         'assignment_id.exists' => ValidationType::Exists->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'files.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'files.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //         'text.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'text.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'text.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assignment_id' => FieldName::AssignmentId->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //         'text' => FieldName::Text->getMessage(),
    //     ];
    // }
}
