<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class MessageRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return false;
    // }

    protected function onIndex() {
        return [
            'chat_id' => ['required', 'integer', 'exists:chats,id'],
            'page' => ['required', 'integer'],
            'page_size' => ['nullable', 'integer'],
        ];
    }

    protected function onStore() {
        return [
            'chat_id' => ['required', 'integer', 'exists:chats,id'],
            'message' => ['required', 'string'],
            'is_read' => ['nullable', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'message' => ['required', 'string'],
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

    public function messages(): array
    {
        return [
            'chat_id.required' => ValidationType::Required->getMessage(),
            'chat_id.integer' => ValidationType::Integer->getMessage(),
            'chat_id.exists' => ValidationType::Exists->getMessage(),
            'page.required' => ValidationType::Required->getMessage(),
            'page.integer' => ValidationType::Integer->getMessage(),
            'page_size.integer' => ValidationType::Integer->getMessage(),
            'message.required' => ValidationType::Required->getMessage(),
            'message.string' => ValidationType::String->getMessage(),
            'is_read.boolean' => ValidationType::Boolean->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'chat_id' => FieldName::ChatId->getMessage(),
            'page' => FieldName::Page->getMessage(),
            'page_size' => FieldName::PageSize->getMessage(),
            'message' => FieldName::Message->getMessage(),
            'is_read' => FieldName::IsRead->getMessage(),
        ];
    }
}
