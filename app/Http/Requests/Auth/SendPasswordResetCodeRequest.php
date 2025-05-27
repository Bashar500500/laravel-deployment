<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class SendPasswordResetCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.required' => ValidationType::Required->getMessage(),
    //         'email.email' => ValidationType::Email->getMessage(),
    //         'email.exists' => ValidationType::Exists->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'email' => FieldName::Email->getMessage(),
    //     ];
    // }
}
