<?php

namespace App\Http\Requests\Badge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Badge\BadgeCategory;
use App\Enums\Badge\BadgeSubCategory;
use App\Enums\Badge\BadgeDifficulty;
use App\Enums\Badge\BadgeStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class BadgeRequest extends FormRequest
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
            'description' => ['sometimes', 'string'],
            'category' => ['required', new Enum(BadgeCategory::class)],
            'sub_category' => ['sometimes', new Enum(BadgeSubCategory::class)],
            'difficulty' => ['required', new Enum(BadgeDifficulty::class)],
            'icon' => ['sometimes', 'string'],
            'color' => ['sometimes', 'string'],
            'shape' => ['sometimes', 'string'],
            'image_url' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(BadgeStatus::class)],
            'reward' => ['required', 'array'],
            'reward.points' => ['required', 'integer', 'gt:0'],
            'reward.xp' => ['required', 'integer', 'gt:0'],
        ];
    }

    protected function onUpdate() {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'category' => ['sometimes', new Enum(BadgeCategory::class)],
            'sub_category' => ['required_with:category', new Enum(BadgeSubCategory::class)],
            'difficulty' => ['sometimes', new Enum(BadgeDifficulty::class)],
            'icon' => ['sometimes', 'string'],
            'color' => ['sometimes', 'string'],
            'shape' => ['sometimes', 'string'],
            'image_url' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(BadgeStatus::class)],
            'reward' => ['sometimes', 'array'],
            'reward.points' => ['required_with:reward', 'integer', 'gt:0'],
            'reward.xp' => ['required_with:reward', 'integer', 'gt:0'],
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
    //         'sub_category.required' => ValidationType::Required->getMessage(),
    //         'sub_category.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'sub_category.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'difficulty.required' => ValidationType::Required->getMessage(),
    //         'difficulty.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'icon.required' => ValidationType::Required->getMessage(),
    //         'icon.string' => ValidationType::String->getMessage(),
    //         'image_url.required' => ValidationType::Required->getMessage(),
    //         'image_url.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'reward.required' => ValidationType::Required->getMessage(),
    //         'reward.array' => ValidationType::Array->getMessage(),
    //         'reward.points.required' => ValidationType::Required->getMessage(),
    //         'reward.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'reward.points.integer' => ValidationType::Integer->getMessage(),
    //         'reward.points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'reward.xp.required' => ValidationType::Required->getMessage(),
    //         'reward.xp.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'reward.xp.integer' => ValidationType::Integer->getMessage(),
    //         'reward.xp.gt' => ValidationType::GreaterThanZero->getMessage(),
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
    //         'sub_category' => FieldName::SubCategory->getMessage(),
    //         'difficulty' => FieldName::Difficulty->getMessage(),
    //         'icon' => FieldName::Icon->getMessage(),
    //         'image_url' => FieldName::ImageUrl->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'reward' => FieldName::Reward->getMessage(),
    //         'reward.points' => FieldName::Points->getMessage(),
    //         'reward.xp' => FieldName::Xp->getMessage(),
    //     ];
    // }
}
