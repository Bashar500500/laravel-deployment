<?php

namespace App\DataTransferObjects\Assessment;

use App\Http\Requests\Assessment\AssessmentSubmitRequest;

class AssessmentSubmitDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?array $answers,
    ) {}

    public static function fromRequest(AssessmentSubmitRequest $request): AssessmentSubmitDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
            answers: $request->validated('answers'),
        );
    }
}
