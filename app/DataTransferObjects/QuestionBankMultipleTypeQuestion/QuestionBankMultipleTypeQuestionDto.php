<?php

namespace App\DataTransferObjects\QuestionBankMultipleTypeQuestion;

use App\Http\Requests\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRequest;
use App\Enums\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionType;
use App\Enums\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionDifficulty;

class QuestionBankMultipleTypeQuestionDto
{
    public function __construct(
        public readonly ?int $questionBankId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?QuestionBankMultipleTypeQuestionType $type,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?QuestionBankMultipleTypeQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?array $options,
        public readonly ?array $additionalSettings,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(QuestionBankMultipleTypeQuestionRequest $request): QuestionBankMultipleTypeQuestionDto
    {
        return new self(
            questionBankId: $request->validated('question_bank_id'),
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

    public static function fromStoreRequest(QuestionBankMultipleTypeQuestionRequest $request): QuestionBankMultipleTypeQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: $request->validated('question_bank_id'),
            type: QuestionBankMultipleTypeQuestionType::from($request->validated('type')),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: QuestionBankMultipleTypeQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            options: $request->validated('options'),
            additionalSettings: $request->validated('additional_settings'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(QuestionBankMultipleTypeQuestionRequest $request): QuestionBankMultipleTypeQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: null,
            type: $request->validated('type') ?
                QuestionBankMultipleTypeQuestionType::from($request->validated('type')) :
                null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                QuestionBankMultipleTypeQuestionDifficulty::from($request->validated('difficulty')) :
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
