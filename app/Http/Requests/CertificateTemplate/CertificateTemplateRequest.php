<?php

namespace App\Http\Requests\CertificateTemplate;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class CertificateTemplateRequest extends FormRequest
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
            'color' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'color' => ['sometimes', 'string'],
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
    //         'color.required' => ValidationType::Required->getMessage(),
    //         'color.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'color' => FieldName::Color->getMessage(),
    //     ];
    // }
}
