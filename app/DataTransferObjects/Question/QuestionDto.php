<?php

namespace App\DataTransferObjects\Question;

use App\Http\Requests\Question\QuestionRequest;
use App\Enums\Question\QuestionCorrectAnswer;
use Illuminate\Http\UploadedFile;

class QuestionDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?string $category,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $question,
        public readonly ?string $optionA,
        public readonly ?string $optionB,
        public readonly ?string $optionC,
        public readonly ?string $optionD,
        public readonly ?QuestionCorrectAnswer $correctAnswer,
        public readonly ?string $codeSnippets,
        public readonly ?string $answerExplanation,
        public readonly ?UploadedFile $questionImage,
        public readonly ?string $videoLink,
    ) {}

    public static function fromIndexRequest(QuestionRequest $request): QuestionDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            category: $request->validated('category'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            question: null,
            optionA: null,
            optionB: null,
            optionC: null,
            optionD: null,
            correctAnswer: null,
            codeSnippets: null,
            answerExplanation: null,
            questionImage: null,
            videoLink: null,
        );
    }

    public static function fromStoreRequest(QuestionRequest $request): QuestionDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            category: $request->validated('category'),
            currentPage: null,
            pageSize: null,
            question: $request->validated('question'),
            optionA: $request->validated('option_a'),
            optionB: $request->validated('option_b'),
            optionC: $request->validated('option_c'),
            optionD: $request->validated('option_d'),
            correctAnswer: QuestionCorrectAnswer::from($request->validated('correct_answer')),
            codeSnippets: $request->validated('code_snippets'),
            answerExplanation: $request->validated('answer_explanation'),
            questionImage: $request->validated('question_image') ?
                UploadedFile::createFromBase($request->validated('question_image')) :
                null,
            videoLink: $request->validated('video_link'),
        );
    }
    public static function fromUpdateRequest(QuestionRequest $request): QuestionDto
    {
        return new self(
            courseId: null,
            category: $request->validated('category'),
            currentPage: null,
            pageSize: null,
            question: $request->validated('question'),
            optionA: $request->validated('option_a'),
            optionB: $request->validated('option_b'),
            optionC: $request->validated('option_c'),
            optionD: $request->validated('option_d'),
            correctAnswer: $request->validated('correct_answer') ?
                QuestionCorrectAnswer::from($request->validated('correct_answer')) :
                null,
            codeSnippets: $request->validated('code_snippets'),
            answerExplanation: $request->validated('answer_explanation'),
            questionImage: $request->validated('question_image') ?
                UploadedFile::createFromBase($request->validated('question_image')) :
                null,
            videoLink: $request->validated('video_link'),
        );
    }
}
