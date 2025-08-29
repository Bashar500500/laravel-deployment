<?php

namespace App\Http\Requests\ContentEngagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ContentEngagement\ContentEngagementContentType;
use App\Enums\ContentEngagement\ContentEngagementEngagementType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ContentEngagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'content_type' => ['required', new Enum(ContentEngagementContentType::class)],
            'engagement_type' => ['required', new Enum(ContentEngagementEngagementType::class)],
            'engagement_value' => ['required', 'string'],
            'engagement_data' => ['required', 'array'],
        ];
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
