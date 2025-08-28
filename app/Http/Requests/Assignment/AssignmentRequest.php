<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Assignment\AssignmentStatus;
use App\Enums\Assignment\AssignmentType;
use App\Enums\Assignment\AssignmentAllowedFileTypes;
use App\Enums\Assignment\AssignmentLateSubmissionPolicy;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssignmentRequest extends FormRequest
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
            'rubric_id' => ['required', 'exists:rubrics,id'],
            'title' => ['required', 'string'],
            'status' => ['required', new Enum(AssignmentStatus::class)],
            'description' => ['sometimes', 'string'],
            'instructions' => ['sometimes', 'string'],
            'due_date' => ['required', 'date', 'date_format:Y-m-d'],
            'points' => ['required', 'integer', 'gt:0'],
            'peer_review_settings' => ['required', 'array'],
            'peer_review_settings.is_peer_reviewed' => ['required', 'boolean'],
            'peer_review_settings.allowed_reviews' => ['required', 'integer'],
            'submission_settings' => ['sometimes', 'array'],
            'submission_settings.type' => ['sometimes', new Enum(AssignmentType::class)],
            'submission_settings.allowed_file_types' => ['sometimes', 'array'],
            'submission_settings.allowed_file_types.*' => ['required_with:submission_settings.allowed_file_types', new Enum(AssignmentAllowedFileTypes::class)],
            'submission_settings.max_file_size_mb' => ['sometimes', 'integer', 'gt:0'],
            'submission_settings.group_assignment' => ['sometimes', 'boolean'],
            'submission_settings.plagiarism_check' => ['sometimes', 'boolean'],
            'policies' => ['sometimes', 'array'],
            'policies.late_submission' => ['sometimes', 'array'],
            'policies.late_submission.policy' => ['sometimes', new Enum(AssignmentLateSubmissionPolicy::class)],
            'policies.late_submission.penalty_percentage' => ['sometimes', 'integer', 'gt:0', 'max:100'],
            'policies.late_submission.cutoff_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:due_date'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
        ];
    }

    protected function onUpdate() {
        return [
            'rubric_id' => ['sometimes', 'exists:rubrics,id'],
            'title' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(AssignmentStatus::class)],
            'description' => ['sometimes', 'string'],
            'instructions' => ['sometimes', 'string'],
            'due_date' => ['required_with:policies.late_submission.cutoff_date', 'date', 'date_format:Y-m-d'],
            'points' => ['sometimes', 'integer', 'gt:0'],
            'peer_review_settings' => ['sometimes', 'array'],
            'peer_review_settings.is_peer_reviewed' => ['required_with:peer_review_settings', 'boolean'],
            'peer_review_settings.allowed_reviews' => ['required_with:peer_review_settings', 'integer'],
            'submission_settings' => ['sometimes', 'array'],
            'submission_settings.type' => ['required_with:submission_settings', new Enum(AssignmentType::class)],
            'submission_settings.allowed_file_types' => ['sometimes', 'array'],
            'submission_settings.allowed_file_types.*' => ['required_with:submission_settings.allowed_file_types', new Enum(AssignmentAllowedFileTypes::class)],
            'submission_settings.max_file_size_mb' => ['sometimes', 'integer', 'gt:0'],
            'submission_settings.group_assignment' => ['required_with:submission_settings', 'boolean'],
            'submission_settings.plagiarism_check' => ['required_with:submission_settings', 'boolean'],
            'policies' => ['sometimes', 'array'],
            'policies.late_submission' => ['required_with:policies', 'array'],
            'policies.late_submission.policy' => ['required_with:policies.late_submission', new Enum(AssignmentLateSubmissionPolicy::class)],
            'policies.late_submission.penalty_percentage' => ['sometimes', 'integer', 'gt:0', 'max:100'],
            'policies.late_submission.cutoff_date' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:due_date'],
            'files' => ['sometimes', 'array'],
            'files.*' => ['required_with:files', 'file'],
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
    //         'rubric_id.required' => ValidationType::Required->getMessage(),
    //         'rubric_id.exists' => ValidationType::Exists->getMessage(),
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'instructions.required' => ValidationType::Required->getMessage(),
    //         'instructions.string' => ValidationType::String->getMessage(),
    //         'due_date.required' => ValidationType::Required->getMessage(),
    //         'due_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'due_date.date' => ValidationType::Date->getMessage(),
    //         'due_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'points.required' => ValidationType::Required->getMessage(),
    //         'points.integer' => ValidationType::Integer->getMessage(),
    //         'points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'submission_settings.required' => ValidationType::Required->getMessage(),
    //         'submission_settings.array' => ValidationType::Array->getMessage(),
    //         'submission_settings.type.required' => ValidationType::Required->getMessage(),
    //         'submission_settings.type.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'submission_settings.type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'submission_settings.allowed_file_types.array' => ValidationType::Array->getMessage(),
    //         'submission_settings.allowed_file_types.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'submission_settings.allowed_file_types.*.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'submission_settings.max_file_size_mb.integer' => ValidationType::Integer->getMessage(),
    //         'submission_settings.max_file_size_mb.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'submission_settings.group_assignment.required' => ValidationType::Required->getMessage(),
    //         'submission_settings.group_assignment.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'submission_settings.group_assignment.boolean' => ValidationType::Boolean->getMessage(),
    //         'submission_settings.plagiarism_check.required' => ValidationType::Required->getMessage(),
    //         'submission_settings.plagiarism_check.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'submission_settings.plagiarism_check.boolean' => ValidationType::Boolean->getMessage(),
    //         'policies.required' => ValidationType::Required->getMessage(),
    //         'policies.array' => ValidationType::Array->getMessage(),
    //         'policies.late_submission.required' => ValidationType::Required->getMessage(),
    //         'policies.late_submission.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'policies.late_submission.array' => ValidationType::Array->getMessage(),
    //         'policies.late_submission.policy.required' => ValidationType::Required->getMessage(),
    //         'policies.late_submission.policy.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'policies.late_submission.policy.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'policies.late_submission.penalty_percentage.integer' => ValidationType::Integer->getMessage(),
    //         'policies.late_submission.penalty_percentage.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'policies.late_submission.penalty_percentage.max' => ValidationType::Max->getMessage(),
    //         'policies.late_submission.cutoff_date.date' => ValidationType::Date->getMessage(),
    //         'policies.late_submission.cutoff_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'policies.late_submission.cutoff_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'files.array' => ValidationType::Array->getMessage(),
    //         'files.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'files.*.file' => ValidationType::File->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'rubric_id' => FieldName::RubricId->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'instructions' => FieldName::Instructions->getMessage(),
    //         'due_date' => FieldName::DueDate->getMessage(),
    //         'points' => FieldName::Points->getMessage(),
    //         'submission_settings' => FieldName::SubmissionSettings->getMessage(),
    //         'submission_settings.type' => FieldName::Type->getMessage(),
    //         'submission_settings.allowed_file_types' => FieldName::AllowedFileTypes->getMessage(),
    //         'submission_settings.allowed_file_types.*' => FieldName::AllowedFileTypes->getMessage(),
    //         'submission_settings.max_file_size_mb' => FieldName::MaxFileSizeMb->getMessage(),
    //         'submission_settings.group_assignment' => FieldName::GroupAssignment->getMessage(),
    //         'submission_settings.plagiarism_check' => FieldName::PlagiarismCheck->getMessage(),
    //         'policies' => FieldName::Policies->getMessage(),
    //         'policies.late_submission' => FieldName::LateSubmission->getMessage(),
    //         'policies.late_submission.policy' => FieldName::Policy->getMessage(),
    //         'policies.late_submission.penalty_percentage' => FieldName::PenaltyPercentage->getMessage(),
    //         'policies.late_submission.cutoff_date' => FieldName::CutoffDate->getMessage(),
    //         'files' => FieldName::Files->getMessage(),
    //         'files.*' => FieldName::Files->getMessage(),
    //     ];
    // }
}
