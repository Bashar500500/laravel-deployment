<?php

namespace App\DataTransferObjects\QuestionBankFillInBlankQuestion;

use App\Http\Requests\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRequest;
use App\Enums\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionDifficulty;

class QuestionBankFillInBlankQuestionDto
{
    public function __construct(
        public readonly ?int $questionBankId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?QuestionBankFillInBlankQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?array $blanks,
        public readonly ?array $displayOptions,
        public readonly ?array $gradingOptions,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(QuestionBankFillInBlankQuestionRequest $request): QuestionBankFillInBlankQuestionDto
    {
        return new self(
            questionBankId: $request->validated('question_bank_id'),
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

    public static function fromStoreRequest(QuestionBankFillInBlankQuestionRequest $request): QuestionBankFillInBlankQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: $request->validated('question_bank_id'),
            text: $request->validated('text'),
            difficulty: QuestionBankFillInBlankQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            blanks: $request->validated('blanks'),
            displayOptions: $request->validated('display_options'),
            gradingOptions: $request->validated('grading_options'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(QuestionBankFillInBlankQuestionRequest $request): QuestionBankFillInBlankQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: null,
            text: $request->validated('text'),
            difficulty: $request->validated('difficulty') ?
                QuestionBankFillInBlankQuestionDifficulty::from($request->validated('difficulty')) :
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
