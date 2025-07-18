<?php

namespace App\DataTransferObjects\QuestionBankShortAnswerQuestion;

use App\Http\Requests\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRequest;
use App\Enums\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionDifficulty;
use App\Enums\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionAnswerType;

class QuestionBankShortAnswerQuestionDto
{
    public function __construct(
        public readonly ?int $questionBankId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $text,
        public readonly ?int $points,
        public readonly ?QuestionBankShortAnswerQuestionDifficulty $difficulty,
        public readonly ?string $category,
        public readonly ?bool $required,
        public readonly ?QuestionBankShortAnswerQuestionAnswerType $answerType,
        public readonly ?int $characterLimit,
        public readonly ?array $acceptedAnswers,
        public readonly ?array $settings,
        public readonly ?array $feedback,
    ) {}

    public static function fromIndexRequest(QuestionBankShortAnswerQuestionRequest $request): QuestionBankShortAnswerQuestionDto
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
            answerType: null,
            characterLimit: null,
            acceptedAnswers: null,
            settings: null,
            feedback: null,
        );
    }

    public static function fromStoreRequest(QuestionBankShortAnswerQuestionRequest $request): QuestionBankShortAnswerQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: $request->validated('question_bank_id'),
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: QuestionBankShortAnswerQuestionDifficulty::from($request->validated('difficulty')),
            category: $request->validated('category'),
            required: $request->validated('required'),
            answerType: QuestionBankShortAnswerQuestionAnswerType::from($request->validated('answer_type')),
            characterLimit: $request->validated('character_limit'),
            acceptedAnswers: $request->validated('accepted_answers'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }

    public static function fromUpdateRequest(QuestionBankShortAnswerQuestionRequest $request): QuestionBankShortAnswerQuestionDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            questionBankId: null,
            text: $request->validated('text'),
            points: $request->validated('points'),
            difficulty: $request->validated('difficulty') ?
                QuestionBankShortAnswerQuestionDifficulty::from($request->validated('difficulty')) :
                null,
            category: $request->validated('category'),
            required: $request->validated('required'),
            answerType: $request->validated('answer_type') ?
                QuestionBankShortAnswerQuestionAnswerType::from($request->validated('answer_type')) :
                null,
            characterLimit: $request->validated('character_limit'),
            acceptedAnswers: $request->validated('accepted_answers'),
            settings: $request->validated('settings'),
            feedback: $request->validated('feedback'),
        );
    }
}
