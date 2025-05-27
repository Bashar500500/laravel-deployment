<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\User\UserRole;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', new Enum(UserRole::class)],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'first_name.required' => ValidationType::Required->getMessage(),
    //         'first_name.string' => ValidationType::String->getMessage(),
    //         'first_name.max' => ValidationType::Max->getMessage(),
    //         'last_name.required' => ValidationType::Required->getMessage(),
    //         'last_name.string' => ValidationType::String->getMessage(),
    //         'last_name.max' => ValidationType::Max->getMessage(),
    //         'email.required' => ValidationType::Required->getMessage(),
    //         'email.string' => ValidationType::String->getMessage(),
    //         'email.email' => ValidationType::Email->getMessage(),
    //         'email.unique' => ValidationType::Unique->getMessage(),
    //         'password.required' => ValidationType::Required->getMessage(),
    //         'password.string' => ValidationType::String->getMessage(),
    //         'password.min' => ValidationType::Min->getMessage(),
    //         'password.confirmed' => ValidationType::Confirmed->getMessage(),
    //         'role.required' => ValidationType::Required->getMessage(),
    //         'role.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'first_name' => FieldName::FirstName->getMessage(),
    //         'last_name' => FieldName::LastName->getMessage(),
    //         'email' => FieldName::Email->getMessage(),
    //         'password' => FieldName::Password->getMessage(),
    //         'role' => FieldName::Role->getMessage(),
    //     ];
    // }
}
