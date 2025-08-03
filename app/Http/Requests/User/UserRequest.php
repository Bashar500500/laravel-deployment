<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\User\UserRole;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['sometimes', 'exists:courses,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', new Enum(UserRole::class)],
            'fcm_token' => ['sometimes', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'unique:users,email'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'fcm_token' => ['sometimes', 'string'],
        ];
    }

    public function rules()
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else if (request()->isMethod('post'))
        {
            return $this->onStore();
        }
        else
        {
            return $this->onUpdate();
        }
    }

    // public function messages(): array
    // {
    //     return [
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
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
    //         'fcm_token.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'first_name' => FieldName::FirstName->getMessage(),
    //         'last_name' => FieldName::LastName->getMessage(),
    //         'email' => FieldName::Email->getMessage(),
    //         'password' => FieldName::Password->getMessage(),
    //         'role' => FieldName::Role->getMessage(),
    //         'fcm_token' => FieldName::FcmToken->getMessage(),
    //     ];
    // }
}
