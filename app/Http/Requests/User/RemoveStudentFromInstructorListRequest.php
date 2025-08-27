<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class RemoveStudentFromInstructorListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => ['required', 'exists:instructor_students,student_id'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'student_id.required' => ValidationType::Required->getMessage(),
    //         'student_id.exists' => ValidationType::Exists->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'student_id' => FieldName::StudentId->getMessage(),
    //     ];
    // }
}
