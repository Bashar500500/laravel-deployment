<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Notification\NotificationType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class NotificationRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return false;
    // }

    protected function onIndex() {
        return [
            'page' => ['required', 'integer'],
            'page_size' => ['nullable', 'integer'],
        ];
    }

    protected function onStore() {
        return [
            'type' => ['required', new Enum(NotificationType::class)],
            'issuer_id' => [
                'required',
                ($this->request->get('type') == NotificationType::User->getType() ?
                    'exists:users,id' :
                    $this->request->get('type') == NotificationType::Version->getType()) ?
                    'exists:versions,id' :
                    'exists:websites,id',
            ],
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ];
    }

    public function rules(): array
    {
        return request()->isMethod('get') ? $this->onIndex() : $this->onStore();
    }

    public function messages(): array
    {
        return [
            'page.required' => ValidationType::Required->getMessage(),
            'page.integer' => ValidationType::Integer->getMessage(),
            'page_size.integer' => ValidationType::Integer->getMessage(),
            'type.required' => ValidationType::Required->getMessage(),
            'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
            'issuer_id.required' => ValidationType::Required->getMessage(),
            'issuer_id.exists' => ValidationType::Exists->getMessage(),
            'title.required' => ValidationType::Required->getMessage(),
            'title.string' => ValidationType::String->getMessage(),
            'body.required' => ValidationType::Required->getMessage(),
            'body.string' => ValidationType::String->getMessage(),
        ];
    }

    public function attributes(): array
    {
        return [
            'page' => FieldName::Page->getMessage(),
            'page_size' => FieldName::PageSize->getMessage(),
            'type' => FieldName::Type->getMessage(),
            'issuer_id' => FieldName::IssuerId->getMessage(),
            'title' => FieldName::Title->getMessage(),
            'body' => FieldName::Body->getMessage(),
        ];
    }
}
