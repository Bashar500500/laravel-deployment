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
            'file' => ['required_if:type,==,File Upload', 'missing_if:type,==,Text Entry', 'file'],
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
    //         'file.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'file.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'file.file' => ValidationType::File->getMessage(),
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
    //         'file' => FieldName::File->getMessage(),
    //         'text' => FieldName::Text->getMessage(),
    //     ];
    // }
}
