<?php

namespace App\Http\Requests\EnrollmentOption;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\EnrollmentOption\EnrollmentOptionType;
use App\Enums\EnrollmentOption\EnrollmentOptionPeriod;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class EnrollmentOptionRequest extends FormRequest
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
            'type' => ['required', new Enum(EnrollmentOptionType::class)],
            'period' => ['required', new Enum(EnrollmentOptionPeriod::class)],
            'allow_self_enrollment' => ['required', 'boolean'],
            'enable_waiting_list' => ['required', 'boolean'],
            'require_instructor_approval' => ['required', 'boolean'],
            'require_prerequisites' => ['required', 'boolean'],
            'enable_notifications' => ['required', 'boolean'],
            'enable_emails' => ['required', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'type' => ['sometimes', new Enum(EnrollmentOptionType::class)],
            'period' => ['sometimes', new Enum(EnrollmentOptionPeriod::class)],
            'allow_self_enrollment' => ['sometimes', 'boolean'],
            'enable_waiting_list' => ['sometimes', 'boolean'],
            'require_instructor_approval' => ['sometimes', 'boolean'],
            'require_prerequisites' => ['sometimes', 'boolean'],
            'enable_notifications' => ['sometimes', 'boolean'],
            'enable_emails' => ['sometimes', 'boolean'],
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
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'period.required' => ValidationType::Required->getMessage(),
    //         'period.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'allow_self_enrollment.required' => ValidationType::Required->getMessage(),
    //         'allow_self_enrollment.boolean' => ValidationType::Boolean->getMessage(),
    //         'enable_waiting_list.required' => ValidationType::Required->getMessage(),
    //         'enable_waiting_list.boolean' => ValidationType::Boolean->getMessage(),
    //         'require_instructor_approval.required' => ValidationType::Required->getMessage(),
    //         'require_instructor_approval.boolean' => ValidationType::Boolean->getMessage(),
    //         'require_prerequisites.required' => ValidationType::Required->getMessage(),
    //         'require_prerequisites.boolean' => ValidationType::Boolean->getMessage(),
    //         'enable_notifications.required' => ValidationType::Required->getMessage(),
    //         'enable_notifications.boolean' => ValidationType::Boolean->getMessage(),
    //         'enable_emails.required' => ValidationType::Required->getMessage(),
    //         'enable_emails.boolean' => ValidationType::Boolean->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'period' => FieldName::Period->getMessage(),
    //         'allow_self_enrollment' => FieldName::AllowSelfEnrollment->getMessage(),
    //         'enable_waiting_list' => FieldName::EnableWaitingList->getMessage(),
    //         'require_instructor_approval' => FieldName::RequireInstructorApproval->getMessage(),
    //         'require_prerequisites' => FieldName::RequirePrerequisites->getMessage(),
    //         'enable_notifications' => FieldName::EnableNotifications->getMessage(),
    //         'enable_emails' => FieldName::EnableEmails->getMessage(),
    //     ];
    // }
}
