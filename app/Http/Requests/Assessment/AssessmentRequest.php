<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Assessment\AssessmentType;
use App\Enums\Assessment\AssessmentStatus;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssessmentRequest extends FormRequest
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
            'time_limit_id' => ['required', 'exists:time_limits,id'],
            'type' => ['required', new Enum(AssessmentType::class)],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(AssessmentStatus::class)],
            'weight' => ['required', 'integer'],
            'available_from' => ['required', 'date', 'date_format:Y-m-d'],
            'available_to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:available_from'],
            'attempts_allowed' => ['required', 'integer', 'gt:0'],
            'shuffle_questions' => ['required', 'boolean'],
            'feedback_options' => ['required', 'array'],
            'feedback_options.show_correct_answers' => ['required', 'boolean'],
            'feedback_options.custom_feedback_message' => ['sometimes', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'time_limit_id' => ['sometimes', 'exists:time_limits,id'],
            'type' => ['sometimes', new Enum(AssessmentType::class)],
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(AssessmentStatus::class)],
            'weight' => ['sometimes', 'integer'],
            'available_from' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'available_to' => ['required_with:available_from', 'date', 'date_format:Y-m-d', 'after_or_equal:available_from'],
            'attempts_allowed' => ['sometimes', 'integer', 'gt:0'],
            'shuffle_questions' => ['sometimes', 'boolean'],
            'feedback_options' => ['sometimes', 'array'],
            'feedback_options.show_correct_answers' => ['required_with:feedback_options', 'boolean'],
            'feedback_options.custom_feedback_message' => ['sometimes', 'string'],
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
    //         'time_limit_id.required' => ValidationType::Required->getMessage(),
    //         'time_limit_id.exists' => ValidationType::Exists->getMessage(),
    //         'type.required' => ValidationType::Required->getMessage(),
    //         'type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'title.required' => ValidationType::Required->getMessage(),
    //         'title.string' => ValidationType::String->getMessage(),
    //         'description.required' => ValidationType::Required->getMessage(),
    //         'description.string' => ValidationType::String->getMessage(),
    //         'status.required' => ValidationType::Required->getMessage(),
    //         'status.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'weight.required' => ValidationType::Required->getMessage(),
    //         'weight.integer' => ValidationType::Integer->getMessage(),
    //         'available_from.required' => ValidationType::Required->getMessage(),
    //         'available_from.date' => ValidationType::Date->getMessage(),
    //         'available_from.date_format' => ValidationType::DateFormat->getMessage(),
    //         'available_to.required' => ValidationType::Required->getMessage(),
    //         'available_to.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'available_to.date' => ValidationType::Date->getMessage(),
    //         'available_to.date_format' => ValidationType::DateFormat->getMessage(),
    //         'available_to.after_or_equal' => ValidationType::AfterOrEqual->getMessage(),
    //         'attempts_allowed.required' => ValidationType::Required->getMessage(),
    //         'attempts_allowed.integer' => ValidationType::Integer->getMessage(),
    //         'attempts_allowed.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'shuffle_questions.required' => ValidationType::Required->getMessage(),
    //         'shuffle_questions.boolean' => ValidationType::Boolean->getMessage(),
    //         'feedback_options.required' => ValidationType::Required->getMessage(),
    //         'feedback_options.array' => ValidationType::Array->getMessage(),
    //         'feedback_options.show_correct_answers.required' => ValidationType::Required->getMessage(),
    //         'feedback_options.show_correct_answers.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'feedback_options.show_correct_answers.boolean' => ValidationType::Boolean->getMessage(),
    //         'feedback_options.custom_feedback_message.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'course_id' => FieldName::CourseId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'time_limit_id' => FieldName::TimeLimitId->getMessage(),
    //         'type' => FieldName::Type->getMessage(),
    //         'title' => FieldName::Title->getMessage(),
    //         'description' => FieldName::Description->getMessage(),
    //         'status' => FieldName::Status->getMessage(),
    //         'weight' => FieldName::Weight->getMessage(),
    //         'available_from' => FieldName::AvailableFrom->getMessage(),
    //         'available_to' => FieldName::AvailableTo->getMessage(),
    //         'attempts_allowed' => FieldName::AttemptsAllowed->getMessage(),
    //         'shuffle_questions' => FieldName::ShuffleQuestions->getMessage(),
    //         'feedback_options' => FieldName::FeedbackOptions->getMessage(),
    //         'feedback_options.show_correct_answers' => FieldName::ShowCorrectAnswers->getMessage(),
    //         'feedback_options.custom_feedback_message' => FieldName::CustomFeedbackMessage->getMessage(),
    //     ];
    // }
}
