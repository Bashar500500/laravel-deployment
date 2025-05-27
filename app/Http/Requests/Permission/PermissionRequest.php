<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\User\UserRole;
use App\Enums\Permission\PermissionGuardName;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }
    protected function onStore() {
        return [
            'name' => ['required', 'string', 'unique:permissions,name'],
            'guard_name' => ['sometimes', new Enum(PermissionGuardName::class)],
            'role' => ['required', new Enum(UserRole::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string', 'unique:permissions,name'],
            'guard_name' => ['sometimes', new Enum(PermissionGuardName::class)],
            'role' => ['sometimes', new Enum(UserRole::class)],
        ];
    }

    public function rules(): array
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
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'name.unique' => ValidationType::Unique->getMessage(),
    //         'guard_name.required' => ValidationType::Required->getMessage(),
    //         'guard_name.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'role.required' => ValidationType::Required->getMessage(),
    //         'role.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'guard_name' => FieldName::GuardName->getMessage(),
    //         'role' => FieldName::Role->getMessage(),
    //     ];
    // }
}
