<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Leave\LeaveType;
use App\Enums\Leave\LeaveStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class LeaveRequest extends FormRequest
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
            'type' => ['required', new Enum(LeaveType::class)],
            'from' => ['required', 'date', 'date_format:Y-m-d'],
            'to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
            'number_of_days' => ['required', 'integer', 'gt:0'],
            'reason' => ['required', 'string'],
            'status' => ['required', new Enum(LeaveStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'type' => ['sometimes', new Enum(LeaveType::class)],
            'from' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'to' => ['required_with:from', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
            'number_of_days' => ['sometimes', 'integer', 'gt:0'],
            'reason' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(LeaveStatus::class)],
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
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'from.required' => ValidationType::Required->getMessage(),
    //         'from.date' => ValidationType::Date->getMessage(),
    //         'from.date_format' => ValidationType::DateFormat->getMessage(),
    //         'to.required' => ValidationType::Required->getMessage(),
    //         'to.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'to.date' => ValidationType::Date->getMessage(),
    //         'to.date_format' => ValidationType::DateFormat->getMessage(),
    //         'to.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'number_of_days.required' => ValidationType::Required->getMessage(),
    //         'number_of_days.integer' => ValidationType::Integer->getMessage(),
    //         'number_of_days.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'reason.required' => ValidationType::Required->getMessage(),
    //         'reason.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'from' => FieldName::From->getMessage(),
    //         'to' => FieldName::To->getMessage(),
    //         'number_of_days' => FieldName::NumberOfDays->getMessage(),
    //         'reason' => FieldName::Reason->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //     ];
    // }
}
