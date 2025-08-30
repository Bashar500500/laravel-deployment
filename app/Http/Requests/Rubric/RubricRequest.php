<?php

namespace App\Http\Requests\Rubric;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Rubric\RubricType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class RubricRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'type' => ['required', new Enum(RubricType::class)],
            'description' => ['required', 'string'],
            'rubric_criterias' => ['required', 'array'],
            'rubric_criterias.*.name' => ['required', 'string'],
            'rubric_criterias.*.weight' => ['required', 'integer'],
            'rubric_criterias.*.description' => ['required', 'string'],
            'rubric_criterias.*.levels' => ['required', 'array'],
            'rubric_criterias.*.levels.excellent' => ['required', 'array'],
            'rubric_criterias.*.levels.excellent.points' => ['required', 'integer', 'lte:rubric_criterias.*.weight'],
            'rubric_criterias.*.levels.excellent.description' => ['required', 'string'],
            'rubric_criterias.*.levels.good' => ['required', 'array'],
            'rubric_criterias.*.levels.good.points' => ['required', 'integer', 'lt:rubric_criterias.*.levels.excellent.points'],
            'rubric_criterias.*.levels.good.description' => ['required', 'string'],
            'rubric_criterias.*.levels.satisfactory' => ['required', 'array'],
            'rubric_criterias.*.levels.satisfactory.points' => ['required', 'integer', 'lt:rubric_criterias.*.levels.good.points'],
            'rubric_criterias.*.levels.satisfactory.description' => ['required', 'string'],
            'rubric_criterias.*.levels.needsImprovement' => ['required', 'array'],
            'rubric_criterias.*.levels.needsImprovement.points' => ['required', 'integer', 'lt:rubric_criterias.*.levels.satisfactory.points'],
            'rubric_criterias.*.levels.needsImprovement.description' => ['required', 'string'],
            'rubric_criterias.*.levels.unsatisfactory' => ['required', 'array'],
            'rubric_criterias.*.levels.unsatisfactory.points' => ['required', 'integer', 'lt:rubric_criterias.*.levels.needsImprovement.points'],
            'rubric_criterias.*.levels.unsatisfactory.description' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'title' => ['sometimes', 'string'],
            'type' => ['sometimes', new Enum(RubricType::class)],
            'description' => ['sometimes', 'string'],
            'rubric_criterias' => ['sometimes', 'array'],
            'rubric_criterias.*.name' => ['required_with:rubric_criterias', 'string'],
            'rubric_criterias.*.weight' => ['required_with:rubric_criterias', 'integer'],
            'rubric_criterias.*.description' => ['required_with:rubric_criterias', 'string'],
            'rubric_criterias.*.levels' => ['required_with:rubric_criterias', 'array'],
            'rubric_criterias.*.levels.excellent' => ['required_with:rubric_criterias.*.levels', 'array'],
            'rubric_criterias.*.levels.excellent.points' => ['required_with:rubric_criterias.*.levels.excellent', 'integer', 'lte:rubric_criterias.*.weight'],
            'rubric_criterias.*.levels.excellent.description' => ['required_with:rubric_criterias.*.levels.excellent', 'string'],
            'rubric_criterias.*.levels.good' => ['required_with:rubric_criterias.*.levels', 'array'],
            'rubric_criterias.*.levels.good.points' => ['required_with:rubric_criterias.*.levels.good', 'integer', 'lt:rubric_criterias.*.levels.excellent.points'],
            'rubric_criterias.*.levels.good.description' => ['required_with:rubric_criterias.*.levels.good', 'string'],
            'rubric_criterias.*.levels.satisfactory' => ['required_with:rubric_criterias.*.levels', 'array'],
            'rubric_criterias.*.levels.satisfactory.points' => ['required_with:rubric_criterias.*.levels.satisfactory', 'integer', 'lt:rubric_criterias.*.levels.good.points'],
            'rubric_criterias.*.levels.satisfactory.description' => ['required_with:rubric_criterias.*.levels.satisfactory', 'string'],
            'rubric_criterias.*.levels.needsImprovement' => ['required_with:rubric_criterias.*.levels', 'array'],
            'rubric_criterias.*.levels.needsImprovement.points' => ['required_with:rubric_criterias.*.levels.needsImprovement', 'integer', 'lt:rubric_criterias.*.levels.satisfactory.points'],
            'rubric_criterias.*.levels.needsImprovement.description' => ['required_with:rubric_criterias.*.levels.needsImprovement', 'string'],
            'rubric_criterias.*.levels.unsatisfactory' => ['required_with:rubric_criterias.*.levels', 'array'],
            'rubric_criterias.*.levels.unsatisfactory.points' => ['required_with:rubric_criterias.*.levels.unsatisfactory', 'integer', 'lt:rubric_criterias.*.levels.needsImprovement.points'],
            'rubric_criterias.*.levels.unsatisfactory.description' => ['required_with:rubric_criterias.*.levels.unsatisfactory', 'string'],
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
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.name.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.name.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.name.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.weight.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.weight.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.weight.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.levels.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.excellent.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.excellent.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.excellent.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.excellent.points.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.excellent.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.excellent.points.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.levels.excellent.points.lte' => ValidationType::LessThanOrEqual->getMessage(),
    //         'rubric_criterias.*.levels.excellent.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.excellent.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.excellent.description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.levels.good.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.good.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.good.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.good.points.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.good.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.good.points.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.levels.good.points.ls' => ValidationType::LessThan->getMessage(),
    //         'rubric_criterias.*.levels.good.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.good.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.good.description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.levels.s1.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s1.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s1.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.s1.points.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s1.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s1.points.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.levels.s1.points.ls' => ValidationType::LessThan->getMessage(),
    //         'rubric_criterias.*.levels.s1.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s1.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s1.description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.levels.s2.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s2.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s2.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.s2.points.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s2.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s2.points.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.levels.s2.points.ls' => ValidationType::LessThan->getMessage(),
    //         'rubric_criterias.*.levels.s2.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.s2.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.s2.description.string' => ValidationType::String->getMessage(),
    //         'rubric_criterias.*.levels.bad.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.bad.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.bad.array' => ValidationType::Array->getMessage(),
    //         'rubric_criterias.*.levels.bad.points.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.bad.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.bad.points.integer' => ValidationType::Integer->getMessage(),
    //         'rubric_criterias.*.levels.bad.points.ls' => ValidationType::LessThan->getMessage(),
    //         'rubric_criterias.*.levels.bad.description.required' => ValidationType::Required->getMessage(),
    //         'rubric_criterias.*.levels.bad.description.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'rubric_criterias.*.levels.bad.description.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'rubric_criterias' => FieldName::RubricCriterias->getMessage(),
    //         'rubric_criterias.*.name' => FieldName::Name->getMessage(),
    //         'rubric_criterias.*.weight' => FieldName::Weight->getMessage(),
    //         'rubric_criterias.*.description' => FieldName::Description->getMessage(),
    //         'rubric_criterias.*.levels' => FieldName::Levels->getMessage(),
    //         'rubric_criterias.*.levels.excellent' => FieldName::Excellent->getMessage(),
    //         'rubric_criterias.*.levels.excellent.points' => FieldName::ExcellentPoints->getMessage(),
    //         'rubric_criterias.*.levels.excellent.description' => FieldName::ExcellentDescription->getMessage(),
    //         'rubric_criterias.*.levels.good' => FieldName::Good->getMessage(),
    //         'rubric_criterias.*.levels.good.points' => FieldName::GoodPoints->getMessage(),
    //         'rubric_criterias.*.levels.good.description' => FieldName::GoodDescription->getMessage(),
    //         'rubric_criterias.*.levels.s1' => FieldName::S1->getMessage(),
    //         'rubric_criterias.*.levels.s1.points' => FieldName::S1Points->getMessage(),
    //         'rubric_criterias.*.levels.s1.description' => FieldName::S1Description->getMessage(),
    //         'rubric_criterias.*.levels.s2' => FieldName::S2->getMessage(),
    //         'rubric_criterias.*.levels.s2.points' => FieldName::S2Points->getMessage(),
    //         'rubric_criterias.*.levels.s2.description' => FieldName::S2Description->getMessage(),
    //         'rubric_criterias.*.levels.bad' => FieldName::Bad->getMessage(),
    //         'rubric_criterias.*.levels.bad.points' => FieldName::BadPoints->getMessage(),
    //         'rubric_criterias.*.levels.bad.description' => FieldName::BadDescription->getMessage(),
    //     ];
    // }
}
