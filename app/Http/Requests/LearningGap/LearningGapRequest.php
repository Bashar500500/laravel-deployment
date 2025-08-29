<?php

namespace App\Http\Requests\LearningGap;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\LearningGap\LearningGapTargetRole;
use App\Enums\LearningGap\LearningGapCurrentLevel;
use App\Enums\LearningGap\LearningGapRequiredLevel;
use App\Enums\LearningGap\LearningGapGapSize;
use App\Enums\LearningGap\LearningGapPriority;
use App\Enums\LearningGap\LearningGapStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class LearningGapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'student_id' => ['required', 'exists:users,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'student_id' => ['required', 'exists:users,id'],
            'skill_id' => ['required', 'exists:skills,id'],
            'target_role' => ['required', new Enum(LearningGapTargetRole::class)],
            'current_level' => ['required', new Enum(LearningGapCurrentLevel::class)],
            'required_level' => ['required', new Enum(LearningGapRequiredLevel::class)],
            'gap_size' => ['required', new Enum(LearningGapGapSize::class)],
            'priority' => ['required', new Enum(LearningGapPriority::class)],
            'gap_score' => ['required', 'decimal:0,2'],
            'status' => ['required', new Enum(LearningGapStatus::class)],
        ];
    }

    protected function onUpdate() {
        return [
            'skill_id' => ['sometimes', 'exists:skills,id'],
            'target_role' => ['sometimes', new Enum(LearningGapTargetRole::class)],
            'current_level' => ['sometimes', new Enum(LearningGapCurrentLevel::class)],
            'required_level' => ['sometimes', new Enum(LearningGapRequiredLevel::class)],
            'gap_size' => ['sometimes', new Enum(LearningGapGapSize::class)],
            'priority' => ['sometimes', new Enum(LearningGapPriority::class)],
            'gap_score' => ['sometimes', 'decimal:0,2'],
            'status' => ['sometimes', new Enum(LearningGapStatus::class)],
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
