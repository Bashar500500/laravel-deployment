<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Assessment\AssessmentSubmitQuestionType;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AssessmentSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assessment_id' => ['required', 'exists:assessments,id'],
            'answers' => ['required', 'array'],
            'answers.*.question_type' => ['required', new Enum(AssessmentSubmitQuestionType::class)],
            'answers.*.is_question_bank_question' => ['required', 'boolean'],
            'answers.*.question_id' => ['required', 'integer', 'gt:0'],
            // 'answers.*.question_id' => [
            //     'required',
            //     $this->request->get('answers.*.is_question_bank_question') == true
            //         ? (
            //             $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::FillInBlankQuestion->getType()
            //                 ? 'exists:question_bank_fill_in_blank_questions,id'
            //                 : (
            //                     $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::MultipleTypeQuestion->getType()
            //                         ? 'exists:question_bank_multiple_type_questions,id'
            //                         : (
            //                             $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::ShortAnswerQuestion->getType()
            //                                 ? 'exists:question_bank_short_answer_questions,id'
            //                                 : 'exists:question_bank_true_or_false_questions,id'
            //                         )
            //                 )
            //         )
            //         : (
            //             $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::FillInBlankQuestion->getType()
            //                 ? 'exists:assessment_fill_in_blank_questions,id'
            //                 : (
            //                     $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::MultipleTypeQuestion->getType()
            //                         ? 'exists:assessment_multiple_type_questions,id'
            //                         : (
            //                             $this->request->get('answers.*.question_type') == AssessmentSubmitQuestionType::ShortAnswerQuestion->getType()
            //                                 ? 'exists:assessment_short_answer_questions,id'
            //                                 : 'exists:assessment_true_or_false_questions,id'
            //                         )
            //                 )
            //         )
            // ],
            'answers.*.option_ids' => ['required_if:answers.*.question_type,==,' . implode(',', AssessmentSubmitQuestionType::MultipleTypeAndTrueOrFalse->getEnumsWithinValue()), 'missing_if:answers.*.question_type,==,' . implode(',', AssessmentSubmitQuestionType::MultipleTypeAndTrueOrFalse->getEnumsExceptValue()), 'array'],
            'answers.*.option_ids.*' => ['required_with:answers.*.option_ids', 'exists:options,id'],
            'answers.*.blanks' => ['required_if:answers.*.question_type,==,FillInBlankQuestion', 'missing_if:answers.*.question_type,==,' . implode(',', AssessmentSubmitQuestionType::FillInBlankQuestion->getEnumsExceptValue()), 'array'],
            'answers.*.blanks.*.blank_id' => ['required_with:answers.*.blanks', 'exists:blanks,id'],
            'answers.*.blanks.*.answer' => ['required_with:answers.*.blanks', 'string'],
            'answers.*.answer' => ['required_if:answers.*.question_type,==,ShortAnswerQuestion', 'missing_if:answers.*.question_type,==,' . implode(',', AssessmentSubmitQuestionType::ShortAnswerQuestion->getEnumsExceptValue()), 'string'],
            'answers.*.time_spent' => ['required', 'integer', 'gt:0'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'assessment_id.required' => ValidationType::Required->getMessage(),
    //         'assessment_id.exists' => ValidationType::Exists->getMessage(),
    //         'answers.required' => ValidationType::Required->getMessage(),
    //         'answers.array' => ValidationType::Array->getMessage(),
    //         'answers.*.question_type.required' => ValidationType::Required->getMessage(),
    //         'answers.*.question_type.Illuminate\Validation\Rules\Enum' => ValidationType::Enum->getMessage(),
    //         'answers.*.is_question_bank_question.required' => ValidationType::Required->getMessage(),
    //         'answers.*.is_question_bank_question.boolean' => ValidationType::Boolean->getMessage(),
    //         'answers.*.question_id.required' => ValidationType::Required->getMessage(),
    //         'answers.*.question_id.exists' => ValidationType::Exists->getMessage(),
    //         'answers.*.question_id.integer' => ValidationType::Integer->getMessage(),
    //         'answers.*.question_id.gt' => ValidationType::GreaterThanZero->getMessage(),
    //         'answers.*.option_ids.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'answers.*.option_ids.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'answers.*.option_ids.array' => ValidationType::Array->getMessage(),
    //         'answers.*.option_ids.*.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'answers.*.option_ids.*.exists' => ValidationType::Exists->getMessage(),
    //         'answers.*.blanks.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'answers.*.blanks.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'answers.*.blanks.array' => ValidationType::Array->getMessage(),
    //         'answers.*.blanks.blank_id.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'answers.*.blanks.blank_id.exists' => ValidationType::Exists->getMessage(),
    //         'answers.*.blanks.answer.required_with' => ValidationType::RequiredWith->getMessage(),
    //         'answers.*.blanks.answer.string' => ValidationType::String->getMessage(),
    //         'answers.*.answer.required_if' => ValidationType::RequiredIf->getMessage(),
    //         'answers.*.answer.missing_if' => ValidationType::MissingIf->getMessage(),
    //         'answers.*.answer.string' => ValidationType::String->getMessage(),
    //         'answers.*.time_spent.required' => ValidationType::Required->getMessage(),
    //         'answers.*.time_spent.integer' => ValidationType::Integer->getMessage(),
    //         'answers.*.time_spent.gt' => ValidationType::GreaterThanZero->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assessment_id' => FieldName::AssessmentId->getMessage(),
    //         'answers' => FieldName::Answers->getMessage(),
    //         'answers.*.question_type' => FieldName::QuestionType->getMessage(),
    //         'answers.*.is_question_bank_question' => FieldName::IsQuestionBankQuestion->getMessage(),
    //         'answers.*.question_id' => FieldName::QuestionId->getMessage(),
    //         'answers.*.option_ids' => FieldName::OptionIds->getMessage(),
    //         'answers.*.option_ids.*' => FieldName::OptionIds->getMessage(),
    //         'answers.*.blanks' => FieldName::Blanks->getMessage(),
    //         'answers.*.blanks.blank_id' => FieldName::BlankId->getMessage(),
    //         'answers.*.blanks.answer' => FieldName::Answer->getMessage(),
    //         'answers.*.answer' => FieldName::Answer->getMessage(),
    //         'answers.*.time_spent' => FieldName::TimeSpent->getMessage(),
    //     ];
    // }
}
