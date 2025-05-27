<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.required' => ValidationType::Required->getMessage(),
    //         'email.string' => ValidationType::String->getMessage(),
    //         'password.required' => ValidationType::Required->getMessage(),
    //         'password.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'email' => FieldName::Email->getMessage(),
    //         'password' => FieldName::Password->getMessage(),
    //     ];
    // }
}
