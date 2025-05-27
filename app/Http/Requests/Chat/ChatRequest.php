<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Chat\ChatType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ChatRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return false;
    // }

    protected function onIndex() {
        return [
            'type' => ['required', new Enum(ChatType::class)],
            'page' => ['required', 'integer'],
            'page_size' => ['nullable', 'integer'],
        ];
    }

    protected function onStore() {
        return [
            'type' => ['required', new Enum(ChatType::class)],
            'issuer_id' => [
                'required',
                $this->request->get('type') == ChatType::Direct->getType() ?
                    'exists:users,id' :
                    'exists:lessons,id',
            ]
        ];
    }

    public function rules(): array
    {
        return request()->isMethod('get') ? $this->onIndex() : $this->onStore();
    }

    public function messages(): array
    {
        return [
            'type.required' => ValidationType::Required->getMessage(),
            'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
            'page.required' => ValidationType::Required->getMessage(),
            'page.integer' => ValidationType::Integer->getMessage(),
            'page_size.integer' => ValidationType::Integer->getMessage(),
            'issuer_id.required' => ValidationType::Required->getMessage(),
            'issuer_id.exists' => ValidationType::Exists->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => FieldName::Type->getMessage(),
            'page' => FieldName::Page->getMessage(),
            'page_size' => FieldName::PageSize->getMessage(),
            'issuer_id' => FieldName::IssuerId->getMessage(),
        ];
    }
}
