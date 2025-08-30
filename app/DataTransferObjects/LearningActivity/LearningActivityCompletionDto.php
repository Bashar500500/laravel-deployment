<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;
use App\Enums\LearningActivity\LearningActivityCompletionType;

class LearningActivityCompletionDto
{
    public function __construct(
        public readonly ?LearningActivityCompletionType $type,
        public readonly ?int $minDuration,
        public readonly ?int $passingScore,
        public readonly ?array $rules,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityCompletionDto
    {
        $type = $request->validated('completion.type') ?
            LearningActivityCompletionType::from($request->validated('completion.type')) : null;
        return match ($type) {
            LearningActivityCompletionType::View => LearningActivityCompletionDto::fromViewType($request),
            LearningActivityCompletionType::Score => LearningActivityCompletionDto::fromScoreType($request),
            LearningActivityCompletionType::Composite => LearningActivityCompletionDto::fromCompositeType($request),
            null => new self(
                type: null,
                minDuration: null,
                passingScore: null,
                rules: null,
            )
        };
    }

    public static function fromViewType(LearningActivityRequest $request): LearningActivityCompletionDto
    {
        return new self(
            type: $request->validated('completion.type') ?
                LearningActivityCompletionType::from($request->validated('completion.type')) :
                null,
            minDuration: $request->validated('completion.minDuration'),
            passingScore: null,
            rules: null,
        );
    }

    public static function fromScoreType(LearningActivityRequest $request): LearningActivityCompletionDto
    {
        return new self(
            type: $request->validated('completion.type') ?
                LearningActivityCompletionType::from($request->validated('completion.type')) :
                null,
            minDuration: null,
            passingScore: $request->validated('completion.passingScore'),
            rules: null,
        );
    }

    public static function fromCompositeType(LearningActivityRequest $request): LearningActivityCompletionDto
    {
        return new self(
            type: $request->validated('completion.type') ?
                LearningActivityCompletionType::from($request->validated('completion.type')) :
                null,
            minDuration: null,
            passingScore: null,
            rules: $request->validated('completion.rules'),
        );
    }
}
