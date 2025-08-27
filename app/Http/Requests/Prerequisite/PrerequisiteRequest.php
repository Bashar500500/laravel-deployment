<?php

namespace App\Http\Requests\Prerequisite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Prerequisite\PrerequisiteType;
use App\Enums\Prerequisite\PrerequisiteAppliesTo;
use App\Enums\Prerequisite\PrerequisiteCondition;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class PrerequisiteRequest extends FormRequest
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
            'type' => ['required', new Enum(PrerequisiteType::class)],
            'prerequisite' => [
                'required',
                $this->request->get('type') == PrerequisiteType::Course->getType() ?
                    'exists:courses,id' :
                    'exists:sections,id',
            ],
            'applies_to' => ['required', new Enum(PrerequisiteAppliesTo::class)],
            'required_for' => [
                'required',
                $this->request->get('applies_to') == PrerequisiteAppliesTo::EntireCourse->getType() ?
                    'exists:courses,id' :
                    'exists:sections,id',
            ],
            'condition' => ['required', new Enum(PrerequisiteCondition::class)],
            'allow_override' => ['required', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'condition' => ['sometimes', new Enum(PrerequisiteCondition::class)],
            'allow_override' => ['sometimes', 'boolean'],
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
    //         'prerequisite.required' => ValidationType::Required->getMessage(),
    //         'prerequisite.exists' => ValidationType::Exists->getMessage(),
    //         'applies_to.required' => ValidationType::Required->getMessage(),
    //         'applies_to.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'required_for.required' => ValidationType::Required->getMessage(),
    //         'required_for.exists' => ValidationType::Exists->getMessage(),
    //         'condition.required' => ValidationType::Required->getMessage(),
    //         'condition.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'allow_override.required' => ValidationType::Required->getMessage(),
    //         'allow_override.boolean' => ValidationType::Boolean->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'prerequisite' => FieldName::Prerequisite->getMessage(),
    //         'applies_to' => FieldName::AppliesTo->getMessage(),
    //         'required_for' => FieldName::RequiredFor->getMessage(),
    //         'condition' => FieldName::Condition->getMessage(),
    //         'allow_override' => FieldName::AllowOverride->getMessage(),
    //     ];
    // }
}
