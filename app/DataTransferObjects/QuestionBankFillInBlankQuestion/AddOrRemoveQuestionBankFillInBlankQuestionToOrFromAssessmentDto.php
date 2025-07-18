<?php

namespace App\DataTransferObjects\QuestionBankFillInBlankQuestion;

use App\Http\Requests\QuestionBankFillInBlankQuestion\AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest;

class AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto
{
    public function __construct(
        public readonly ?int $assessmentId,
    ) {}

    public static function fromRequest(AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentRequest $request): AddOrRemoveQuestionBankFillInBlankQuestionToOrFromAssessmentDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
        );
    }
}
