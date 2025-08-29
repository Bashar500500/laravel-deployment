<?php

namespace App\Http\Requests\LearningRecommendation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\LearningRecommendation\LearningRecommendationRecommendationType;
use App\Enums\LearningRecommendation\LearningRecommendationStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class LearningRecommendationRequest extends FormRequest
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
            'gap_id' => ['required', 'exists:learning_gaps,id'],
            'recommendation_type' => ['required', new Enum(LearningRecommendationRecommendationType::class)],
            'resource_id' => ['required', 'integer'],
            'resource_title' => ['required', 'string'],
            'resource_provider' => ['required', 'string'],
            'resource_url' => ['required', 'string'],
            'estimated_duration_hours' => ['required', 'integer'],
            'priority_order' => ['required', 'integer'],
            'status' => ['required', new Enum(LearningRecommendationStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'gap_id' => ['sometimes', 'exists:learning_gaps,id'],
            'recommendation_type' => ['sometimes', new Enum(LearningRecommendationRecommendationType::class)],
            'resource_id' => ['sometimes', 'integer'],
            'resource_title' => ['sometimes', 'string'],
            'resource_provider' => ['sometimes', 'string'],
            'resource_url' => ['sometimes', 'string'],
            'estimated_duration_hours' => ['sometimes', 'integer'],
            'priority_order' => ['sometimes', 'integer'],
            'status' => ['sometimes', new Enum(LearningRecommendationStatus::class)],
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
    //         'date.required' => ValidationType::Required->getMessage(),
    //         'date.date' => ValidationType::Date->getMessage(),
    //         'date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'day.required' => ValidationType::Required->getMessage(),
    //         'day.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'date' => FieldName::Date->getMessage(),
    //         'day' => FieldName::Day->getMessage(),
    //     ];
    // }
}
