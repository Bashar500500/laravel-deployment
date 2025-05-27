<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class VerifyPasswordResetCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.required' => ValidationType::Required->getMessage(),
    //         'email.email' => ValidationType::Email->getMessage(),
    //         'email.exists' => ValidationType::Exists->getMessage(),
    //         'code.required' => ValidationType::Required->getMessage(),
    //         'code.string' => ValidationType::String->getMessage(),
    //         'password.required' => ValidationType::Required->getMessage(),
    //         'password.min' => ValidationType::Min->getMessage(),
    //         'password.confirmed' => ValidationType::Confirmed->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'email' => FieldName::Email->getMessage(),
    //         'code' => FieldName::Code->getMessage(),
    //         'password' => FieldName::Password->getMessage(),
    //     ];
    // }
}
