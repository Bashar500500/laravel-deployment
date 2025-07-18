<?php

namespace App\DataTransferObjects\QuestionBankTrueOrFalseQuestion;

use App\Http\Requests\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRequest;
use App\Enums\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionDifficulty;

class QuestionBankTrueOrFalseQuestionDto
{
    public function __construct(
        public readonly ?int $questionBankId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?QuestionBankTrueOrFalseQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?bool $correctAnswer,
        public readonly ?array $labels,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(QuestionBankTrueOrFalseQuestionRequest $request): QuestionBankTrueOrFalseQuestionDto
    {
        return new self(
            questionBankId: $request->validated('question_bank_id'),
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

    public static function fromStoreRequest(QuestionBankTrueOrFalseQuestionRequest $request): QuestionBankTrueOrFalseQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: $request->validated('question_bank_id'),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: QuestionBankTrueOrFalseQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            correctAnswer: $request->validated('correct_answer'),
            labels: $request->validated('labels'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(QuestionBankTrueOrFalseQuestionRequest $request): QuestionBankTrueOrFalseQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                QuestionBankTrueOrFalseQuestionDifficulty::from($request->validated('difficulty')) :
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
