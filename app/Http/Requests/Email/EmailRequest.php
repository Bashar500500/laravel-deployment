<?php

namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class EmailRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string'],
            'body' => ['required', 'string'],
        ];
    }

    public function rules(): array
    {
        if (request()->isMethod('get'))
        {
            return $this->onIndex();
        }
        else
        {
            return $this->onStore();
        }
    }

    public function messages(): array
    {
        return [
            'page.required' => ValidationType::Required->getMessage(),
            'page.integer' => ValidationType::Integer->getMessage(),
            'page.gt' => ValidationType::GreaterThanZero->getMessage(),
            'page_size.integer' => ValidationType::Integer->getMessage(),
            'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
            'user_id.required' => ValidationType::Required->getMessage(),
            'user_id.exists' => ValidationType::Exists->getMessage(),
            'subject.required' => ValidationType::Required->getMessage(),
            'subject.string' => ValidationType::String->getMessage(),
            'body.required' => ValidationType::Required->getMessage(),
            'body.string' => ValidationType::String->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'page' => FieldName::Page->getMessage(),
            'page_size' => FieldName::PageSize->getMessage(),
            'user_id' => FieldName::UserId->getMessage(),
            'subject' => FieldName::Subject->getMessage(),
            'body' => FieldName::Body->getMessage(),
        ];
    }
}
