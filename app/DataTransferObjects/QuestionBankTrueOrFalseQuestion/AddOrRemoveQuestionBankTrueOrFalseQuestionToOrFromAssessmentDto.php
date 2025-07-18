<?php

namespace App\DataTransferObjects\QuestionBankTrueOrFalseQuestion;

use App\Http\Requests\QuestionBankTrueOrFalseQuestion\AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest;

class AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto
{
    public function __construct(
        public readonly ?int $assessmentId,
    ) {}

    public static function fromRequest(AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentRequest $request): AddOrRemoveQuestionBankTrueOrFalseQuestionToOrFromAssessmentDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
        );
    }
}
