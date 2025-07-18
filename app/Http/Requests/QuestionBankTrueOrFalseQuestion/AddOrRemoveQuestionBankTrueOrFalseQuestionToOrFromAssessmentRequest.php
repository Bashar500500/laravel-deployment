<?php

namespace App\Http\Requests\QuestionBankTrueOrFalseQuestion;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Request\ValidationType;
use App\Enums\Request\FieldName;

class AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assessment_id' => ['required', 'exists:assessments,id'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'assessment_id.required' => ValidationType::Required->getMessage(),
    //         'assessment_id.exists' => ValidationType::Exists->getMessage(),
    //     ];
    // }

    // public function attributes(): array
    // {
    //     return [
    //         'assessment_id' => FieldName::AssessmentId->getMessage(),
    //     ];
    // }
}
