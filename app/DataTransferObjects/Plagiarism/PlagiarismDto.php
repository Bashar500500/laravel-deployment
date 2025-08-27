<?php

namespace App\DataTransferObjects\Plagiarism;

use App\Http\Requests\Plagiarism\PlagiarismRequest;

class PlagiarismDto
{
    public function __construct(
        public readonly ?int $assignmentSubmitId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $score,
    ) {}

    public static function fromIndexRequest(PlagiarismRequest $request): PlagiarismDto
    {
        return new self(
            assignmentSubmitId: $request->validated('assignment_submit_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            score: null,
        );
    }

    public static function fromUpdateRequest(PlagiarismRequest $request): PlagiarismDto
    {
        return new self(
            assignmentSubmitId: null,
            currentPage: null,
            pageSize: null,
            score: $request->validated('score'),
        );
    }
}
