<?php

namespace App\Http\Requests\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Rule\RuleCategory;
use App\Enums\Rule\RuleFrequency;
use App\Enums\Rule\RuleStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class RuleRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'category' => ['required', new Enum(RuleCategory::class)],
            'points' => ['required', 'integer', 'gt:0'],
            'frequency' => ['required', new Enum(RuleFrequency::class)],
            'status' => ['required', new Enum(RuleStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'category' => ['sometimes', new Enum(RuleCategory::class)],
            'points' => ['sometimes', 'integer', 'gt:0'],
            'frequency' => ['sometimes', new Enum(RuleFrequency::class)],
            'status' => ['sometimes', new Enum(RuleStatus::class)],
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
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'points.required' => ValidationType::Required->getMessage(),
    //         'points.integer' => ValidationType::Integer->getMessage(),
    //         'points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'frequency.required' => ValidationType::Required->getMessage(),
    //         'frequency.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'points' => FieldName::Points->getMessage(),
    //         'frequency' => FieldName::Frequency->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //     ];
    // }
}
