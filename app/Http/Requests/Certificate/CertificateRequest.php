<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Certificate\CertificateType;
use App\Enums\Certificate\CertificateCondition;
use App\Enums\Certificate\CertificateStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class CertificateRequest extends FormRequest
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
            'certificate_template_id' => ['required', 'exists:certificate_templates,id'],
            'type' => ['required', new Enum(CertificateType::class)],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'condition' => ['required', new Enum(CertificateCondition::class)],
            'status' => ['required', new Enum(CertificateStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'certificate_template_id' => ['sometimes', 'exists:certificate_templates,id'],
            'type' => ['sometimes', new Enum(CertificateType::class)],
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'condition' => ['sometimes', new Enum(CertificateCondition::class)],
            'status' => ['sometimes', new Enum(CertificateStatus::class)],
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
    //         'certificate_template_id.required' => ValidationType::Required->getMessage(),
    //         'certificate_template_id.exists' => ValidationType::Exists->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'condition.required' => ValidationType::Required->getMessage(),
    //         'condition.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'certificate_template_id' => FieldName::CertificateTemplateId->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'condition' => FieldName::Condition->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //     ];
    // }
}
