<?php

namespace App\Http\Requests\Reply;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ReplyRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return false;
    // }

    protected function onStore() {
        return [
            'message_id' => ['required', 'integer', 'exists:messages,id'],
            'reply' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'reply' => ['required', 'string'],
        ];
    }

    public function rules(): array
    {
        return request()->isMethod('post') ? $this->onStore() : $this->onUpdate();
    }

    public function messages(): array
    {
        return [
            'message_id.required' => ValidationType::Required->getMessage(),
            'message_id.integer' => ValidationType::Integer->getMessage(),
            'message_id.exists' => ValidationType::Exists->getMessage(),
            'reply.required' => ValidationType::Required->getMessage(),
            'reply.string' => ValidationType::String->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'message_id' => FieldName::MessageId->getMessage(),
            'reply' => FieldName::Reply->getMessage(),
        ];
    }
}
