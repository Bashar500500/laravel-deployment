<?php

namespace App\DataTransferObjects\Assignment;

use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\Enums\Assignment\AssignmentSubmitType;

class AssignmentSubmitDto
{
    public function __construct(
        public readonly ?int $assignmentId,
        public readonly ?AssignmentSubmitType $type,
        public readonly ?array $files,
        public readonly ?string $text,
    ) {}

    public static function fromRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: $request->validated('assignment_id'),
            type: AssignmentSubmitType::from($request->validated('type')),
            files: $request->validated('files'),
            text: $request->validated('text'),
        );
    }
}
