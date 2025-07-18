<?php

namespace App\DataTransferObjects\QuestionBankShortAnswerQuestion;

use App\Http\Requests\QuestionBankShortAnswerQuestion\AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest;

class AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto
{
    public function __construct(
        public readonly ?int $assessmentId,
    ) {}

    public static function fromRequest(AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentRequest $request): AddOrRemoveQuestionBankShortAnswerQuestionToOrFromAssessmentDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
        );
    }
}
