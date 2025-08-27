<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;

class LearningActivityContentCaptionsDto
{
    public function __construct(
        public readonly ?string $url,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityContentCaptionsDto
    {
        return new self(
            url: $request->validated('content.data.captions.url'),
        );
    }
}
