<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;

class LearningActivityFlagsDto
{
    public function __construct(
        public readonly ?bool $isFreePreview,
        public readonly ?bool $isCompulsory,
        public readonly ?bool $requiresEnrollment,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityFlagsDto
    {
        return new self(
            isFreePreview: $request->validated('flags.isFreePreview'),
            isCompulsory: $request->validated('flags.isCompulsory'),
            requiresEnrollment: $request->validated('flags.requiresEnrollment'),
        );
    }
}
