<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'capacity' => ['required', 'array'],
            'capacity.min' => ['required', 'integer', 'gt:0'],
            'capacity.max' => ['required', 'integer', 'gt:capacity.min'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'capacity' => ['sometimes', 'array'],
            'capacity.min' => ['required_with:capacity', 'integer', 'gt:0'],
            'capacity.max' => ['required_with:capacity', 'integer', 'gt:capacity.min'],
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
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'image.image' => ValidationType::Image->getMessage(),
    //         'image.mimes' => ValidationType::ImageMimes->getMessage(),
    //         'capacity.required' => ValidationType::Required->getMessage(),
    //         'capacity.array' => ValidationType::Array->getMessage(),
    //         'capacity.min.required' => ValidationType::Required->getMessage(),
    //         'capacity.min.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'capacity.min.integer' => ValidationType::Integer->getMessage(),
    //         'capacity.min.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'capacity.max.required' => ValidationType::Required->getMessage(),
    //         'capacity.max.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'capacity.max.integer' => ValidationType::Integer->getMessage(),
    //         'capacity.max.gt' => ValidationType::GreaterThan->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'image' => FieldName::Image->getMessage(),
    //         'capacity' => FieldName::Capacity->getMessage(),
    //         'capacity.min' => FieldName::Min->getMessage(),
    //         'capacity.max' => FieldName::Max->getMessage(),
    //         'capacity.current' => FieldName::Current->getMessage(),
    //     ];
    // }
}
