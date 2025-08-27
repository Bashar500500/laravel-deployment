<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Progress\ProgressSkillLevel;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class ProgressRequest extends FormRequest
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
            'course_id' => ['required', 'exists:courses,id'],
            'student_id' => ['required', 'exists:users,id'],
            'progress' => ['required', 'integer', 'gte:0'],
            'modules' => ['required', 'string'],
            'last_active' => ['required', 'string'],
            'streak' => ['required', 'string'],
            'skill_level' => ['required', new Enum(ProgressSkillLevel::class)],
            'upcomig' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'progress' => ['sometimes', 'integer', 'gte:0'],
            'modules' => ['sometimes', 'string'],
            'last_active' => ['sometimes', 'string'],
            'streak' => ['sometimes', 'string'],
            'skill_level' => ['sometimes', new Enum(ProgressSkillLevel::class)],
            'upcomig' => ['sometimes', 'string'],
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
    //         'course_id.required' => ValidationType::Required->getMessage(),
    //         'course_id.exists' => ValidationType::Exists->getMessage(),
    //         'student_id.required' => ValidationType::Required->getMessage(),
    //         'student_id.exists' => ValidationType::Exists->getMessage(),
    //         'progress.required' => ValidationType::Required->getMessage(),
    //         'progress.integer' => ValidationType::Integer->getMessage(),
    //         'progress.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'modules.required' => ValidationType::Required->getMessage(),
    //         'modules.string' => ValidationType::String->getMessage(),
    //         'time.required' => ValidationType::Required->getMessage(),
    //         'time.string' => ValidationType::String->getMessage(),
    //         'last_active.required' => ValidationType::Required->getMessage(),
    //         'last_active.string' => ValidationType::String->getMessage(),
    //         'streak.required' => ValidationType::Required->getMessage(),
    //         'streak.string' => ValidationType::String->getMessage(),
    //         'skill_level.required' => ValidationType::Required->getMessage(),
    //         'skill_level.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'upcomig.required' => ValidationType::Required->getMessage(),
    //         'upcomig.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'student_id' => FieldName::StudentId->getMessage(),
    //         'progress' => FieldName::Progress->getMessage(),
    //         'modules' => FieldName::Modules->getMessage(),
    //         'time' => FieldName::Time->getMessage(),
    //         'last_active' => FieldName::LastActive->getMessage(),
    //         'streak' => FieldName::Streak->getMessage(),
    //         'skill_level' => FieldName::SkillLevel->getMessage(),
    //         'upcomig' => FieldName::Upcomig->getMessage(),
    //     ];
    // }
}
