<?php

namespace App\Http\Requests\SubCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\SubCategory\SubCategoryStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class SubCategoryRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string'],
            'status' => ['required', new Enum(SubCategoryStatus::class)],
            'description' => ['required', 'string'],
            'sub_category_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(SubCategoryStatus::class)],
            'description' => ['sometimes', 'string'],
            'sub_category_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
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
    //         'category_id.required' => ValidationType::Required->getMessage(),
    //         'category_id.exists' => ValidationType::Exists->getMessage(),
    //         'name.required' => ValidationType::Required->getMessage(),
    //         'name.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => (ValidationType::Enum)->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'sub_category_image.image' => ValidationType::Image->getMessage(),
    //         'sub_category_image.mimes' => ValidationType::ImageMimes->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'category_id' => FieldName::CategoryId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'sub_category_image' => FieldName::SubCategoryImage->getMessage(),
    //     ];
    // }
}
