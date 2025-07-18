<?php

namespace App\DataTransferObjects\AssessmentFillInBlankQuestion;

use App\Http\Requests\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRequest;
use App\Enums\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionDifficulty;

class AssessmentFillInBlankQuestionDto
{
    public function __construct(
        public readonly ?int $assessmentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?AssessmentFillInBlankQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?array $blanks,
        public readonly ?array $displayOptions,
        public readonly ?array $gradingOptions,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(AssessmentFillInBlankQuestionRequest $request): AssessmentFillInBlankQuestionDto
    {
        return new self(
            assessmentId: $request->validated('assessment_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            text: null,
            difficulty: null,
            category: null,
            required: null,
            blanks: null,
            displayOptions: null,
            gradingOptions: null,
            settings: null,
            feedback: null,
        );
    }

    public static function fromStoreRequest(AssessmentFillInBlankQuestionRequest $request): AssessmentFillInBlankQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: $request->validated('assessment_id'),
            text: $request->validated('text'),
            difficulty: AssessmentFillInBlankQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            blanks: $request->validated('blanks'),
            displayOptions: $request->validated('display_options'),
            gradingOptions: $request->validated('grading_options'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(AssessmentFillInBlankQuestionRequest $request): AssessmentFillInBlankQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assessmentId: null,
            text: $request->validated('text'),
            difficulty: $request->validated('difficulty') ?
                AssessmentFillInBlankQuestionDifficulty::from($request->validated('difficulty')) :
                null,
            category: $request->validated('category'),
            required: $request->validated('required'),
            blanks: $request->validated('blanks'),
            displayOptions: $request->validated('display_options'),
            gradingOptions: $request->validated('grading_options'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }
}
