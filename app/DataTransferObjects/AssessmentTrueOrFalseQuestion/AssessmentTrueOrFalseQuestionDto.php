<?php

namespace App\DataTransferObjects\AssessmentTrueOrFalseQuestion;

use App\Http\Requests\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRequest;
use App\Enums\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionDifficulty;

class AssessmentTrueOrFalseQuestionDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?AssessmentTrueOrFalseQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?bool $correctAnswer,
        public readonly ?array $labels,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(AssessmentTrueOrFalseQuestionRequest $request): AssessmentTrueOrFalseQuestionDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            text: null,
            points: null,
            difficulty: null,
            category: null,
            required: null,
            correctAnswer: null,
            labels: null,
            settings: null,
            feedback: null,
        );
    }

    public static function fromStoreRequest(AssessmentTrueOrFalseQuestionRequest $request): AssessmentTrueOrFalseQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: $request->validated('assessment_id'),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: AssessmentTrueOrFalseQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            correctAnswer: $request->validated('correct_answer'),
            labels: $request->validated('labels'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(AssessmentTrueOrFalseQuestionRequest $request): AssessmentTrueOrFalseQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                AssessmentTrueOrFalseQuestionDifficulty::from($request->validated('difficulty')) :
                null,
            category: $request->validated('category'),
            required: $request->validated('required'),
            correctAnswer: $request->validated('correct_answer'),
            labels: $request->validated('labels'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }
}
