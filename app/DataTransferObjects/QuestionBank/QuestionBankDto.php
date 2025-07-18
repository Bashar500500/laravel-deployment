<?php

namespace App\DataTransferObjects\QuestionBank;

use App\Http\Requests\QuestionBank\QuestionBankRequest;

class QuestionBankDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
    ) {}

    public static function fromIndexRequest(QuestionBankRequest $request): QuestionBankDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
        );
    }

    public static function fromStoreRequest(QuestionBankRequest $request): QuestionBankDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
        );
    }

    // public static function fromUpdateRequest(QuestionBankRequest $request): QuestionBankDto
    // {
    //     return new self(
    //     );
    // }
}
