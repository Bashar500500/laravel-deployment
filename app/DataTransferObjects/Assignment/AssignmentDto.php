<?php

namespace App\DataTransferObjects\Assignment;

use App\Http\Requests\Assignment\AssignmentRequest;
use App\Enums\Assignment\AssignmentStatus;
use Illuminate\Support\Carbon;

class AssignmentDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $rubricId,
        public readonly ?string $title,
        public readonly ?AssignmentStatus $status,
        public readonly ?string $description,
        public readonly ?string $instructions,
        public readonly ?Carbon $dueDate,
        public readonly ?int $points,
        public readonly ?array $peerReviewSettings,
        public readonly ?array $submissionSettings,
        public readonly ?array $policies,
        public readonly ?array $files,
    ) {}

    public static function fromIndexRequest(AssignmentRequest $request): AssignmentDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            rubricId: null,
            title: null,
            status: null,
            description: null,
            instructions: null,
            dueDate: null,
            points: null,
            peerReviewSettings: null,
            submissionSettings: null,
            policies: null,
            files: null,
        );
    }

    public static function fromStoreRequest(AssignmentRequest $request): AssignmentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            rubricId: $request->validated('rubric_id'),
            title: $request->validated('title'),
            status: AssignmentStatus::from($request->validated('status')),
            description: $request->validated('description'),
            instructions: $request->validated('instructions'),
            dueDate: Carbon::parse($request->validated('due_date')),
            points: $request->validated('points'),
            peerReviewSettings: $request->validated('peer_review_settings'),
            submissionSettings: $request->validated('submission_settings'),
            policies: $request->validated('policies'),
            files: $request->validated('files'),
        );
    }

    public static function fromUpdateRequest(AssignmentRequest $request): AssignmentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            rubricId: $request->validated('rubric_id'),
            title: $request->validated('title'),
            status: $request->validated('status') ?
                AssignmentStatus::from($request->validated('status')) :
                null,
            description: $request->validated('description'),
            instructions: $request->validated('instructions'),
            dueDate: $request->validated('due_date') ?
                Carbon::parse($request->validated('due_date')) :
                null,
            points: $request->validated('points'),
            peerReviewSettings: $request->validated('peer_review_settings'),
            submissionSettings: $request->validated('submission_settings'),
            policies: $request->validated('policies'),
            files: $request->validated('files'),
        );
    }
}
