<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class PermissionUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'permission' => ['required', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => ValidationType::Required->getMessage(),
            'user_id.exists' => ValidationType::Exists->getMessage(),
            'permission.required' => ValidationType::Required->getMessage(),
            'permission.exists' => ValidationType::Exists->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => FieldName::UserId->getMessage(),
            'permission' => FieldName::Permission->getMessage(),
        ];
    }
}
