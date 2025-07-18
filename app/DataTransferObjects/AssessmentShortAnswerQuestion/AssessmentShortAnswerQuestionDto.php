<?php

namespace App\DataTransferObjects\AssessmentShortAnswerQuestion;

use App\Http\Requests\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRequest;
use App\Enums\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionDifficulty;
use App\Enums\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionAnswerType;

class AssessmentShortAnswerQuestionDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?AssessmentShortAnswerQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?AssessmentShortAnswerQuestionAnswerType $answerType,
        public readonly ?int $characterLimit,
        public readonly ?array $acceptedAnswers,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(AssessmentShortAnswerQuestionRequest $request): AssessmentShortAnswerQuestionDto
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
            answerType: null,
            characterLimit: null,
            acceptedAnswers: null,
            settings: null,
            feedback: null,
        );
    }

    public static function fromStoreRequest(AssessmentShortAnswerQuestionRequest $request): AssessmentShortAnswerQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: $request->validated('assessment_id'),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: AssessmentShortAnswerQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            answerType: AssessmentShortAnswerQuestionAnswerType::from($request->validated('answer_type')),
            characterLimit: $request->validated('character_limit'),
            acceptedAnswers: $request->validated('accepted_answers'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(AssessmentShortAnswerQuestionRequest $request): AssessmentShortAnswerQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                AssessmentShortAnswerQuestionDifficulty::from($request->validated('difficulty')) :
                null,
            category: $request->validated('category'),
            required: $request->validated('required'),
            answerType: $request->validated('answer_type') ?
                AssessmentShortAnswerQuestionAnswerType::from($request->validated('answer_type')) :
                null,
            characterLimit: $request->validated('character_limit'),
            acceptedAnswers: $request->validated('accepted_answers'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }
}
