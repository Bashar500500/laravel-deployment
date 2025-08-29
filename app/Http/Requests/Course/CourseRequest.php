<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Course\CourseLanguage;
use App\Enums\Course\CourseLevel;
use App\Enums\Course\CourseStatus;
use App\Enums\Course\CourseAccessType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'access_type' => ['sometimes', new Enum(CourseAccessType::class)],
            'all_courses' => ['sometimes', 'boolean'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'language' => ['sometimes', new Enum(CourseLanguage::class)],
            'level' => ['sometimes', new Enum(CourseLevel::class)],
            'timezone' => ['sometimes', 'regex:^(?:Z|[+-](?:2[0-3]|[01][0-9]):[0-5][0-9])$^'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'cover_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'status' => ['required', new Enum(CourseStatus::class)],
            'duration' => ['required', 'integer', 'gt:0'],
            'price' => ['sometimes', 'decimal:0,2', 'gte:0'],
            'code' => ['sometimes', 'string'],
            'access_settings' => ['sometimes', 'array'],
            'access_settings.access_type' => ['sometimes', new Enum(CourseAccessType::class)],
            'access_settings.price_hidden' => ['sometimes', 'boolean'],
            'access_settings.is_secret' => ['sometimes', 'boolean'],
            'access_settings.enrollment_limit' => ['sometimes', 'array'],
            'access_settings.enrollment_limit.enabled' => ['required_with:access_settings.enrollment_limit', 'boolean'],
            'access_settings.enrollment_limit.limit' => ['sometimes', 'missing_if:access_settings.enrollment_limit.enabled,==,false', 'integer', 'gt:0'],
            'features' => ['sometimes', 'array'],
            'features.personalized_learning_paths' => ['sometimes', 'boolean'],
            'features.certificate_requires_submission' => ['sometimes', 'boolean'],
            'features.discussion_features' => ['sometimes', 'array'],
            'features.discussion_features.attach_files' => ['sometimes', 'boolean'],
            'features.discussion_features.create_topics' => ['sometimes', 'boolean'],
            'features.discussion_features.edit_replies' => ['sometimes', 'boolean'],
            'features.student_groups' => ['sometimes', 'boolean'],
            'features.is_featured' => ['sometimes', 'boolean'],
            'features.show_progress_screen' => ['sometimes', 'boolean'],
            'features.hide_grade_totals' => ['sometimes', 'boolean'],
        ];
    }

    protected function onUpdate() {
        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'language' => ['sometimes', new Enum(CourseLanguage::class)],
            'level' => ['sometimes', new Enum(CourseLevel::class)],
            'timezone' => ['sometimes', 'regex:^(?:Z|[+-](?:2[0-3]|[01][0-9]):[0-5][0-9])$^'],
            'start_date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required_with:start_date', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'cover_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'status' => ['sometimes', new Enum(CourseStatus::class)],
            'duration' => ['sometimes', 'integer', 'gt:0'],
            'price' => ['sometimes', 'decimal:0,2', 'gte:0'],
            'access_settings' => ['sometimes', 'array'],
            'access_settings.access_type' => ['sometimes', new Enum(CourseAccessType::class)],
            'access_settings.price_hidden' => ['sometimes', 'boolean'],
            'access_settings.is_secret' => ['sometimes', 'boolean'],
            'access_settings.enrollment_limit' => ['sometimes', 'array'],
            'access_settings.enrollment_limit.enabled' => ['required_with:access_settings.enrollment_limit', 'boolean'],
            'access_settings.enrollment_limit.limit' => ['required_if:access_settings.enrollment_limit.enabled,==,true', 'missing_if:access_settings.enrollment_limit.enabled,==,false', 'missing_if:access_settings.enrollment_limit.enabled,==,null', 'integer', 'gt:0'],
            'features' => ['sometimes', 'array'],
            'features.personalized_learning_paths' => ['sometimes', 'boolean'],
            'features.certificate_requires_submission' => ['sometimes', 'boolean'],
            'features.discussion_features' => ['sometimes', 'array'],
            'features.discussion_features.attach_files' => ['sometimes', 'boolean'],
            'features.discussion_features.create_topics' => ['sometimes', 'boolean'],
            'features.discussion_features.edit_replies' => ['sometimes', 'boolean'],
            'features.student_groups' => ['sometimes', 'boolean'],
            'features.is_featured' => ['sometimes', 'boolean'],
            'features.show_progress_screen' => ['sometimes', 'boolean'],
            'features.hide_grade_totals' => ['sometimes', 'boolean'],
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
    //         'description.string' => ValidationType::String->getMessage(),
    //         'language.required' => ValidationType::Required->getMessage(),
    //         'language.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'level.required' => ValidationType::Required->getMessage(),
    //         'level.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'timezone.required' => ValidationType::Required->getMessage(),
    //         'timezone.regex' => ValidationType::Regex->getMessage(),
    //         'start_date.required' => ValidationType::Required->getMessage(),
    //         'start_date.date' => ValidationType::Date->getMessage(),
    //         'start_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.required' => ValidationType::Required->getMessage(),
    //         'end_date.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'end_date.date' => ValidationType::Date->getMessage(),
    //         'end_date.date_format' => ValidationType::DateFormat->getMessage(),
    //         'end_date.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'cover_image.image' => ValidationType::Image->getMessage(),
    //         'cover_image.mimes' => ValidationType::ImageMimes->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'duration.required' => ValidationType::Required->getMessage(),
    //         'duration.integer' => ValidationType::Integer->getMessage(),
    //         'duration.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'price.required' => ValidationType::Required->getMessage(),
    //         'price.decimal' => ValidationType::Decimal->getMessage(),
    //         'price.gte' => ValidationType::GreaterThanOrEqualZero->getMessage(),
    //         'access_settings.required' => ValidationType::Required->getMessage(),
    //         'access_settings.array' => ValidationType::Array->getMessage(),
    //         'access_settings.access_type.required' => ValidationType::Required->getMessage(),
    //         'access_settings.access_type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'access_settings.price_hidden.required' => ValidationType::Required->getMessage(),
    //         'access_settings.price_hidden.boolean' => ValidationType::Boolean->getMessage(),
    //         'access_settings.is_secret.required' => ValidationType::Required->getMessage(),
    //         'access_settings.is_secret.boolean' => ValidationType::Boolean->getMessage(),
    //         'access_settings.enrollment_limit.required' => ValidationType::Required->getMessage(),
    //         'access_settings.enrollment_limit.array' => ValidationType::Array->getMessage(),
    //         'access_settings.enrollment_limit.enabled.required' => ValidationType::Required->getMessage(),
    //         'access_settings.enrollment_limit.enabled.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'access_settings.enrollment_limit.enabled.boolean' => ValidationType::Boolean->getMessage(),
    //         'access_settings.enrollment_limit.limit.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'access_settings.enrollment_limit.limit.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'access_settings.enrollment_limit.limit.integer' => ValidationType::Integer->getMessage(),
    //         'access_settings.enrollment_limit.limit.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'features.required' => ValidationType::Required->getMessage(),
    //         'features.array' => ValidationType::Array->getMessage(),
    //         'features.personalized_learning_paths.required' => ValidationType::Required->getMessage(),
    //         'features.personalized_learning_paths.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.certificate_requires_submission.required' => ValidationType::Required->getMessage(),
    //         'features.certificate_requires_submission.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.discussion_features.required' => ValidationType::Required->getMessage(),
    //         'features.discussion_features.array' => ValidationType::Array->getMessage(),
    //         'features.discussion_features.attach_files.required' => ValidationType::Required->getMessage(),
    //         'features.discussion_features.attach_files.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.discussion_features.create_topics.required' => ValidationType::Required->getMessage(),
    //         'features.discussion_features.create_topics.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.discussion_features.edit_replies.required' => ValidationType::Required->getMessage(),
    //         'features.discussion_features.edit_replies.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.student_groups.required' => ValidationType::Required->getMessage(),
    //         'features.student_groups.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.is_featured.required' => ValidationType::Required->getMessage(),
    //         'features.is_featured.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.show_progress_screen.required' => ValidationType::Required->getMessage(),
    //         'features.show_progress_screen.boolean' => ValidationType::Boolean->getMessage(),
    //         'features.hide_grade_totals.required' => ValidationType::Required->getMessage(),
    //         'features.hide_grade_totals.boolean' => ValidationType::Boolean->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'category_id' => FieldName::CategoryId->getMessage(),
    //         'name' => FieldName::Name->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'language' => FieldName::Language->getMessage(),
    //         'level' => FieldName::Level->getMessage(),
    //         'timezone' => FieldName::Timezone->getMessage(),
    //         'start_date' => FieldName::StartDate->getMessage(),
    //         'end_date' => FieldName::EndDate->getMessage(),
    //         'cover_image' => FieldName::CoverImage->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'duration' => FieldName::Duration->getMessage(),
    //         'price' => FieldName::Price->getMessage(),
    //         'access_settings' => FieldName::AccessSettings->getMessage(),
    //         'access_settings.access_type' => FieldName::AccessType->getMessage(),
    //         'access_settings.price_hidden' => FieldName::PriceHidden->getMessage(),
    //         'access_settings.is_secret' => FieldName::IsSecret->getMessage(),
    //         'access_settings.enrollment_limit' => FieldName::EnrollmentLimit->getMessage(),
    //         'access_settings.enrollment_limit.enabled' => FieldName::Enabled->getMessage(),
    //         'access_settings.enrollment_limit.limit' => FieldName::Limit->getMessage(),
    //         'features' => FieldName::Features->getMessage(),
    //         'features.personalized_learning_paths' => FieldName::PersonalizedLearningPaths->getMessage(),
    //         'features.certificate_requires_submission' => FieldName::CertificateRequiresSubmission->getMessage(),
    //         'features.discussion_features' => FieldName::DiscussionFeatures->getMessage(),
    //         'features.discussion_features.attach_files' => FieldName::AttachFiles->getMessage(),
    //         'features.discussion_features.create_topics' => FieldName::CreateTopics->getMessage(),
    //         'features.discussion_features.edit_replies' => FieldName::EditReplies->getMessage(),
    //         'features.student_groups' => FieldName::StudentGroups->getMessage(),
    //         'features.is_featured' => FieldName::IsFeatured->getMessage(),
    //         'features.show_progress_screen' => FieldName::ShowProgressScreen->getMessage(),
    //         'features.hide_grade_totals' => FieldName::HideGradeTotals->getMessage(),
    //     ];
    // }
}
