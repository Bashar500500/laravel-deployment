<?php

namespace App\Http\Requests\AssessmentTrueOrFalseQuestion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDifficulty;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssessmentTrueOrFalseQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onIndex() {
        return [
            'assessment_id' => ['required', 'exists:assessments,id'],
            'page' => ['required', 'integer', 'gt:0'],
            'page_size' => ['sometimes', 'integer', 'gt:0'],
        ];
    }

    protected function onStore() {
        return [
            'assessment_id' => ['required', 'exists:assessments,id'],
            'text' => ['required', 'string'],
            'points' => ['required', 'integer', 'gt:0'],
            'difficulty' => ['required', new Enum(AssessmentTrueOrFalseQuestionDifficulty::class)],
            'category' => ['required', 'string'],
            'required' => ['required', 'boolean'],
            'correct_answer' => ['required', 'boolean'],
            'labels' => ['sometimes', 'array'],
            'labels.true' => ['sometimes', 'string'],
            'labels.false' => ['sometimes', 'string'],
            'settings' => ['required', 'array'],
            'settings.time_limit' => ['required', 'integer', 'gt:0'],
            'settings.question_weight' => ['required', 'integer', 'gt:0'],
            'settings.randomize_options' => ['required', 'boolean'],
            'settings.allow_partial_credit' => ['required', 'boolean'],
            'feedback' => ['required', 'array'],
            'feedback.correct' => ['required', 'string'],
            'feedback.incorrect' => ['required', 'string'],
        ];
    }

    protected function onUpdate() {
        return [
            'text' => ['sometimes', 'string'],
            'points' => ['sometimes', 'integer', 'gt:0'],
            'difficulty' => ['sometimes', new Enum(AssessmentTrueOrFalseQuestionDifficulty::class)],
            'category' => ['sometimes', 'string'],
            'required' => ['sometimes', 'boolean'],
            'correct_answer' => ['sometimes', 'boolean'],
            'labels' => ['sometimes', 'array'],
            'labels.true' => ['sometimes', 'string'],
            'labels.false' => ['sometimes', 'string'],
            'settings' => ['sometimes', 'array'],
            'settings.time_limit' => ['required_with:settings', 'integer', 'gt:0'],
            'settings.question_weight' => ['required_with:settings', 'integer', 'gt:0'],
            'settings.randomize_options' => ['required_with:settings', 'boolean'],
            'settings.allow_partial_credit' => ['required_with:settings', 'boolean'],
            'feedback' => ['sometimes', 'array'],
            'feedback.correct' => ['required_with:feedback', 'string'],
            'feedback.incorrect' => ['required_with:feedback', 'string'],
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
    //         'assessment_id.required' => ValidationType::Required->getMessage(),
    //         'assessment_id.exists' => ValidationType::Exists->getMessage(),
    //         'page.required' => ValidationType::Required->getMessage(),
    //         'page.integer' => ValidationType::Integer->getMessage(),
    //         'page.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'page_size.integer' => ValidationType::Integer->getMessage(),
    //         'page_size.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'text.required' => ValidationType::Required->getMessage(),
    //         'text.string' => ValidationType::String->getMessage(),
    //         'points.required' => ValidationType::Required->getMessage(),
    //         'points.integer' => ValidationType::Integer->getMessage(),
    //         'points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'difficulty.required' => ValidationType::Required->getMessage(),
    //         'difficulty.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.string' => ValidationType::String->getMessage(),
    //         'required.required' => ValidationType::Required->getMessage(),
    //         'required.boolean' => ValidationType::Boolean->getMessage(),
    //         'correct_answer.required' => ValidationType::Required->getMessage(),
    //         'correct_answer.boolean' => ValidationType::Boolean->getMessage(),
    //         'labels.array' => ValidationType::Array->getMessage(),
    //         'labels.true.string' => ValidationType::String->getMessage(),
    //         'labels.false.string' => ValidationType::String->getMessage(),
    //         'settings.required' => ValidationType::Required->getMessage(),
    //         'settings.array' => ValidationType::Array->getMessage(),
    //         'settings.time_limit.required' => ValidationType::Required->getMessage(),
    //         'settings.time_limit.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.time_limit.integer' => ValidationType::Integer->getMessage(),
    //         'settings.time_limit.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'settings.question_weight.required' => ValidationType::Required->getMessage(),
    //         'settings.question_weight.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.question_weight.integer' => ValidationType::Integer->getMessage(),
    //         'settings.question_weight.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'settings.randomize_options.required' => ValidationType::Required->getMessage(),
    //         'settings.randomize_options.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.randomize_options.boolean' => ValidationType::Boolean->getMessage(),
    //         'settings.allow_partial_credit.required' => ValidationType::Required->getMessage(),
    //         'settings.allow_partial_credit.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'settings.allow_partial_credit.boolean' => ValidationType::Boolean->getMessage(),
    //         'feedback.required' => ValidationType::Required->getMessage(),
    //         'feedback.array' => ValidationType::Array->getMessage(),
    //         'feedback.correct.required' => ValidationType::Required->getMessage(),
    //         'feedback.correct.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'feedback.correct.string' => ValidationType::String->getMessage(),
    //         'feedback.incorrect.required' => ValidationType::Required->getMessage(),
    //         'feedback.incorrect.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'feedback.incorrect.string' => ValidationType::String->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assessment_id' => FieldName::AssessmentId->getMessage(),
    //         'page' => FieldName::Page->getMessage(),
    //         'page_size' => FieldName::PageSize->getMessage(),
    //         'text' => FieldName::Text->getMessage(),
    //         'points' => FieldName::Points->getMessage(),
    //         'difficulty' => FieldName::Difficulty->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'required' => FieldName::Required->getMessage(),
    //         'correct_answer' => FieldName::CorrectAnswer->getMessage(),
    //         'labels' => FieldName::AdditionalSettings->getMessage(),
    //         'labels.true' => FieldName::True->getMessage(),
    //         'labels.false' => FieldName::False->getMessage(),
    //         'settings' => FieldName::Settings->getMessage(),
    //         'settings.time_limit' => FieldName::TimeLimit->getMessage(),
    //         'settings.question_weight' => FieldName::QuestionWeight->getMessage(),
    //         'settings.randomize_options' => FieldName::RandomizeOptions->getMessage(),
    //         'settings.allow_partial_credit' => FieldName::AllowPartialCredit->getMessage(),
    //         'feedback' => FieldName::Feedback->getMessage(),
    //         'feedback.correct' => FieldName::Correct->getMessage(),
    //         'feedback.incorrect' => FieldName::Incorrect->getMessage(),
    //     ];
    // }
}
