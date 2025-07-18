<?php

namespace App\DataTransferObjects\Assignment;

use App\Http\Requests\Assignment\AssignmentSubmitRequest;
use App\Enums\Assignment\AssignmentSubmitType;
use Illuminate\Http\UploadedFile;

class AssignmentSubmitDto
{
    public function __construct(
        public readonly ?int $assignmentId,
        public readonly ?AssignmentSubmitType $type,
        public readonly ?UploadedFile $file,
        public readonly ?string $text,
    ) {}

    public static function fromRequest(AssignmentSubmitRequest $request): AssignmentSubmitDto
    {
        return new self(
            assignmentId: $request->validated('assignment_id'),
            type: AssignmentSubmitType::from($request->validated('type')),
            file: $request->validated('file') ?
            UploadedFile::createFromBase($request->validated('file')) :
            null,
            text: $request->validated('text'),
        );
    }
}
