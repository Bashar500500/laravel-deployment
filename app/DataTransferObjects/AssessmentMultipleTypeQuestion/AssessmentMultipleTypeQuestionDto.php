<?php

namespace App\DataTransferObjects\AssessmentMultipleTypeQuestion;

use App\Http\Requests\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRequest;
use App\Enums\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionType;
use App\Enums\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionDifficulty;

class AssessmentMultipleTypeQuestionDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?AssessmentMultipleTypeQuestionType $type,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?AssessmentMultipleTypeQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?array $options,
        public readonly ?array $additionalSettings,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(AssessmentMultipleTypeQuestionRequest $request): AssessmentMultipleTypeQuestionDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            text: null,
            points: null,
            difficulty: null,
            category: null,
            required: null,
            options: null,
            additionalSettings: null,
            settings: null,
            feedback: null,
        );
    }

    public static function fromStoreRequest(AssessmentMultipleTypeQuestionRequest $request): AssessmentMultipleTypeQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: $request->validated('assessment_id'),
            type: AssessmentMultipleTypeQuestionType::from($request->validated('type')),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: AssessmentMultipleTypeQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            options: $request->validated('options'),
            additionalSettings: $request->validated('additional_settings'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(AssessmentMultipleTypeQuestionRequest $request): AssessmentMultipleTypeQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: null,
            type: $request->validated('type') ?
                AssessmentMultipleTypeQuestionType::from($request->validated('type')) :
                null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                AssessmentMultipleTypeQuestionDifficulty::from($request->validated('difficulty')) :
                null,
            category: $request->validated('category'),
            required: $request->validated('required'),
            options: $request->validated('options'),
            additionalSettings: $request->validated('additional_settings'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }
}
