<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class RemoveUserFromCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_code' => ['required', 'string', 'exists:user_course_groups,student_code'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'student_code.required' => ValidationType::Required->getMessage(),
    //         'student_code.string' => ValidationType::String->getMessage(),
    //         'student_code.exists' => ValidationType::Exists->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'student_code' => FieldName::StudentCode->getMessage(),
    //     ];
    // }
}
