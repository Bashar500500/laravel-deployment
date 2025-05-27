<?php

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeTrend;
use App\Enums\Grade\GradeResubmission;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class GradeRequest extends FormRequest
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
            // 'assignment_id' => ['required', 'exists:assignment,id'],
            'assignment_id' => ['required', 'integer'],
            'student_id' => ['required', 'exists:users,id'],
            'due_date' => ['required', 'date', 'date_format:Y-m-d'],
            'extended_due_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:due_date'],
            'status' => ['required', new Enum(GradeStatus::class)],
            'points_earned' => ['required', 'integer', 'gte:0'],
            'max_points' => ['required', 'integer', 'min:100', 'max:100'],
            'percentage' => ['required', 'integer', 'gte:0'],
            'category' => ['required', new Enum(GradeCategory::class)],
            'class_average' => ['required', 'decimal:0,2'],
            'trend' => ['required', new Enum(GradeTrend::class)],
            'trend_data' => ['required', 'array'],
            'trend_data.*' => ['required', 'integer', 'gte:0'],
            'feedback' => ['required', 'string'],
            'resubmission' => ['required', new Enum(GradeResubmission::class)],
            'resubmission_due' => ['sometimes', 'date', 'date_format:Y-m-d'],
        ];
    }

    protected function onUpdate() {
        return [
            'due_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'extended_due_date' => ['required_with:due_date', 'date', 'date_format:Y-m-d', 'after_or_equal:due_date'],
            'status' => ['sometimes', new Enum(GradeStatus::class)],
            'points_earned' => ['sometimes', 'integer', 'gte:0'],
            'max_points' => ['sometimes', 'integer', 'min:100', 'max:100'],
            'percentage' => ['sometimes', 'integer', 'gte:0'],
            'category' => ['sometimes', new Enum(GradeCategory::class)],
            'class_average' => ['sometimes', 'decimal:0,2'],
            'trend' => ['sometimes', new Enum(GradeTrend::class)],
            'trend_data' => ['sometimes', 'array'],
            'trend_data.*' => ['required_with:trend_data', 'integer', 'gte:0'],
            'feedback' => ['sometimes', 'string'],
            'resubmission' => ['sometimes', new Enum(GradeResubmission::class)],
            'resubmission_due' => ['sometimes', 'date', 'date_format:Y-m-d'],
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
    //         'assignment_id.required' => ValidationType::Required->getMessage(),
    //         'assignment_id.exists' => ValidationType::Exists->getMessage(),
    //         'student_id.required' => ValidationType::Required->getMessage(),
    //         'student_id.exists' => ValidationType::Exists->getMessage(),
    //         'due_date.required' => ValidationType::Required->getMessage(),
    //         'due_date.date' => ValidationType::Date->getMessage(),
    //         'due_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'extended_due_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'extended_due_date.date' => ValidationType::Date->getMessage(),
    //         'extended_due_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'extended_due_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'points_earned.required' => ValidationType::Required->getMessage(),
    //         'points_earned.integer' => ValidationType::Integer->getMessage(),
    //         'points_earned.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'max_points.required' => ValidationType::Required->getMessage(),
    //         'max_points.integer' => ValidationType::Integer->getMessage(),
    //         'max_points.min' => ValidationType::Min->getMessage(),
    //         'max_points.max' => ValidationType::Max->getMessage(),
    //         'percentage.required' => ValidationType::Required->getMessage(),
    //         'percentage.integer' => ValidationType::Integer->getMessage(),
    //         'percentage.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'class_average.required' => ValidationType::Required->getMessage(),
    //         'class_average.decimal' => ValidationType::Decimal->getMessage(),
    //         'trend_data.array' => ValidationType::Array->getMessage(),
    //         'trend_data.*.required' => ValidationType::Required->getMessage(),
    //         'trend_data.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'trend_data.*.integer' => ValidationType::Integer->getMessage(),
    //         'trend_data.*.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'feedback.required' => ValidationType::Required->getMessage(),
    //         'feedback.string' => ValidationType::String->getMessage(),
    //         'resubmission.required' => ValidationType::Required->getMessage(),
    //         'resubmission.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'resubmission_due.date' => ValidationType::Date->getMessage(),
    //         'resubmission_due.date_format' => ValidationType::DateFormat->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'assignment_id' => FieldName::AssignmentId->getMessage(),
    //         'student_id' => FieldName::StudentId->getMessage(),
    //         'due_date' => FieldName::DueDate->getMessage(),
    //         'extended_due_date' => FieldName::ExtendedDueDate->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'points_earned' => FieldName::PointsEarned->getMessage(),
    //         'max_points' => FieldName::MaxPoints->getMessage(),
    //         'percentage' => FieldName::Percentage->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'class_average' => FieldName::ClassAverage->getMessage(),
    //         'trend' => FieldName::Trend->getMessage(),
    //         'trend_data' => FieldName::TrendData->getMessage(),
    //         'trend_data.*' => FieldName::TrendData->getMessage(),
    //         'feedback' => FieldName::Feedback->getMessage(),
    //         'resubmission' => FieldName::Resubmission->getMessage(),
    //         'resubmission_due' => FieldName::ResubmissionDue->getMessage(),
    //     ];
    // }
}
