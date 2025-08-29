<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AddUserToCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['sometimes', 'string', 'email'],
            'code' => ['sometimes', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
            'student_code' => ['required', 'string', 'min:8', 'max:8'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.required' => ValidationType::Required->getMessage(),
    //         'email.string' => ValidationType::String->getMessage(),
    //         'email.email' => ValidationType::Email->getMessage(),
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'student_code.required' => ValidationType::Required->getMessage(),
    //         'student_code.string' => ValidationType::String->getMessage(),
    //         'student_code.min' => ValidationType::Min->getMessage(),
    //         'student_code.max' => ValidationType::Max->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'email' => FieldName::Page->getMessage(),
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'student_code' => FieldName::StudentCode->getMessage(),
    //     ];
    // }
}
