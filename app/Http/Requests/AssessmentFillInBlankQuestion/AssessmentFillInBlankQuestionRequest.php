<?php

namespace App\Http\Requests\AssessmentFillInBlankQuestion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDifficulty;
use App\Enums\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionBlankStyle;
use App\Enums\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionBlankWidth;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssessmentFillInBlankQuestionRequest extends FormRequest
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
            'difficulty' => ['required', new Enum(AssessmentFillInBlankQuestionDifficulty::class)],
            'category' => ['required', 'string'],
            'required' => ['required', 'boolean'],
            'blanks' => ['required', 'array'],
            'blanks.*.correct_answers' => ['required', 'array'],
            'blanks.*.correct_answers.*' => ['required', 'string'],
            'blanks.*.points' => ['required', 'integer', 'gt:0'],
            'blanks.*.case_sensitive' => ['required', 'boolean'],
            'blanks.*.exact_match' => ['required', 'boolean'],
            'blanks.*.hint' => ['sometimes', 'string'],
            'display_options' => ['required', 'array'],
            'display_options.blank_style' => ['required', new Enum(AssessmentFillInBlankQuestionBlankStyle::class)],
            'display_options.blank_width' => ['required', new Enum(AssessmentFillInBlankQuestionBlankWidth::class)],
            'display_options.show_word_bank' => ['required', 'boolean'],
            'display_options.show_hints' => ['required', 'boolean'],
            'grading_options' => ['required', 'array'],
            'grading_options.allow_partial_credit' => ['required', 'boolean'],
            'grading_options.ignore_extra_spaces' => ['required', 'boolean'],
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
            'difficulty' => ['sometimes', new Enum(AssessmentFillInBlankQuestionDifficulty::class)],
            'category' => ['sometimes', 'string'],
            'required' => ['sometimes', 'boolean'],
            'blanks' => ['sometimes', 'array'],
            'blanks.*.correct_answers' => ['required_with:blanks', 'array'],
            'blanks.*.correct_answers.*' => ['required_with:blanks.correct_answers', 'string'],
            'blanks.*.points' => ['required_with:blanks', 'integer', 'gt:0'],
            'blanks.*.case_sensitive' => ['required_with:blanks', 'boolean'],
            'blanks.*.exact_match' => ['required_with:blanks', 'boolean'],
            'blanks.*.hint' => ['sometimes', 'string'],
            'display_options' => ['sometimes', 'array'],
            'display_options.blank_style' => ['required_with:display_options', new Enum(AssessmentFillInBlankQuestionBlankStyle::class)],
            'display_options.blank_width' => ['required_with:display_options', new Enum(AssessmentFillInBlankQuestionBlankWidth::class)],
            'display_options.show_word_bank' => ['required_with:display_options', 'boolean'],
            'display_options.show_hints' => ['required_with:display_options', 'boolean'],
            'grading_options' => ['sometimes', 'array'],
            'grading_options.allow_partial_credit' => ['required_with:grading_options', 'boolean'],
            'grading_options.ignore_extra_spaces' => ['required_with:grading_options', 'boolean'],
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
    //         'difficulty.required' => ValidationType::Required->getMessage(),
    //         'difficulty.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'category.required' => ValidationType::Required->getMessage(),
    //         'category.string' => ValidationType::String->getMessage(),
    //         'required.required' => ValidationType::Required->getMessage(),
    //         'required.boolean' => ValidationType::Boolean->getMessage(),
    //         'blanks.required' => ValidationType::Required->getMessage(),
    //         'blanks.array' => ValidationType::Array->getMessage(),
    //         'blanks.*.correct_answers.required' => ValidationType::Required->getMessage(),
    //         'blanks.*.correct_answers.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'blanks.*.correct_answers.array' => ValidationType::Array->getMessage(),
    //         'blanks.*.correct_answers.*.required' => ValidationType::Required->getMessage(),
    //         'blanks.*.correct_answers.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'blanks.*.correct_answers.*.string' => ValidationType::String->getMessage(),
    //         'blanks.*.points.required' => ValidationType::Required->getMessage(),
    //         'blanks.*.points.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'blanks.*.points.integer' => ValidationType::Integer->getMessage(),
    //         'blanks.*.points.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'blanks.*.case_sensitive.required' => ValidationType::Required->getMessage(),
    //         'blanks.*.case_sensitive.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'blanks.*.case_sensitive.boolean' => ValidationType::Boolean->getMessage(),
    //         'blanks.*.exact_match.required' => ValidationType::Required->getMessage(),
    //         'blanks.*.exact_match.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'blanks.*.exact_match.boolean' => ValidationType::Boolean->getMessage(),
    //         'blanks.*.hint.string' => ValidationType::String->getMessage(),
    //         'display_options.required' => ValidationType::Required->getMessage(),
    //         'display_options.array' => ValidationType::Array->getMessage(),
    //         'display_options.blank_style.required' => ValidationType::Required->getMessage(),
    //         'display_options.blank_style.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'display_options.blank_style.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'display_options.blank_width.required' => ValidationType::Required->getMessage(),
    //         'display_options.blank_width.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'display_options.blank_width.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'display_options.show_word_bank.required' => ValidationType::Required->getMessage(),
    //         'display_options.show_word_bank.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'display_options.show_word_bank.boolean' => ValidationType::Boolean->getMessage(),
    //         'display_options.show_hints.required' => ValidationType::Required->getMessage(),
    //         'display_options.show_hints.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'display_options.show_hints.boolean' => ValidationType::Boolean->getMessage(),
    //         'grading_options.required' => ValidationType::Required->getMessage(),
    //         'grading_options.array' => ValidationType::Array->getMessage(),
    //         'grading_options.allow_partial_credit.required' => ValidationType::Required->getMessage(),
    //         'grading_options.allow_partial_credit.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'grading_options.allow_partial_credit.boolean' => ValidationType::Boolean->getMessage(),
    //         'grading_options.ignore_extra_spaces.required' => ValidationType::Required->getMessage(),
    //         'grading_options.ignore_extra_spaces.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'grading_options.ignore_extra_spaces.boolean' => ValidationType::Boolean->getMessage(),
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
    //         'difficulty' => FieldName::Difficulty->getMessage(),
    //         'category' => FieldName::Category->getMessage(),
    //         'required' => FieldName::Required->getMessage(),
    //         'blanks' => FieldName::Blanks->getMessage(),
    //         'blanks.*.correct_answers' => FieldName::CorrectAnswers->getMessage(),
    //         'blanks.*.correct_answers.*' => FieldName::CorrectAnswers->getMessage(),
    //         'blanks.*.points' => FieldName::Points->getMessage(),
    //         'blanks.*.case_sensitive' => FieldName::CaseSensitive->getMessage(),
    //         'blanks.*.exact_match' => FieldName::ExactMatch->getMessage(),
    //         'blanks.*.hint' => FieldName::Hint->getMessage(),
    //         'display_options' => FieldName::DisplayOptions->getMessage(),
    //         'display_options.blank_style' => FieldName::BlankStyle->getMessage(),
    //         'display_options.blank_width' => FieldName::BlankWidth->getMessage(),
    //         'display_options.show_word_bank' => FieldName::ShowWordBank->getMessage(),
    //         'display_options.show_hints' => FieldName::ShowHints->getMessage(),
    //         'grading_options' => FieldName::GradingOptions->getMessage(),
    //         'grading_options.allow_partial_credit' => FieldName::AllowPartialCredit->getMessage(),
    //         'grading_options.ignore_extra_spaces' => FieldName::IgnoreExtraSpaces->getMessage(),
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
