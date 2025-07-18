<?php

namespace App\DataTransferObjects\QuestionBankMultipleTypeQuestion;

use App\Http\Requests\QuestionBankMultipleTypeQuestion\AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest;

class AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto
{
    public function __construct(
        public readonly ?int $assessmentId,
    ) {}

    public static function fromRequest(AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentRequest $request): AddOrRemoveQuestionBankMultipleTypeQuestionToOrFromAssessmentDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
        );
    }
}
