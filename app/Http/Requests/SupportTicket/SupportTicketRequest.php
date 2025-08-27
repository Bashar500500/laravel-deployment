<?php

namespace App\Http\Requests\SupportTicket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\SupportTicket\SupportTicketPriority;
use App\Enums\SupportTicket\SupportTicketStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class SupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'category' => ['sometimes', 'string'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'subject' => ['required', 'string'],
            'priority' => ['required', new Enum(SupportTicketPriority::class)],
            'category' => ['required', 'string'],
            'status' => ['required', new Enum(SupportTicketStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'subject' => ['sometimes', 'string'],
            'priority' => ['sometimes', new Enum(SupportTicketPriority::class)],
            'category' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(SupportTicketStatus::class)],
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
    //         'date.required' => ValidationType::Required->getMessage(),
    //         'date.date' => ValidationType::Date->getMessage(),
    //         'date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'subject.required' => ValidationType::Required->getMessage(),
    //         'subject.string' => ValidationType::String->getMessage(),
    //         'priority.required' => ValidationType::Required->getMessage(),
    //         'priority.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'date' => FieldName::Date->getMessage(),
    //         'subject' => FieldName::Subject->getMessage(),
    //         'priority' => FieldName::Priority->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //     ];
    // }
}
