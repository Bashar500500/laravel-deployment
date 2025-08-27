<?php

namespace App\DataTransferObjects\Attendance;

use App\Http\Requests\Attendance\AttendanceRequest;

class AttendanceDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $learningActivityId,
        public readonly ?int $studentId,
        public readonly ?bool $isPresent,
    ) {}

    public static function fromIndexRequest(AttendanceRequest $request): AttendanceDto
    {
        return new self(
            learningActivityId: $request->validated('learning_activity_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            studentId: null,
            isPresent: null,
        );
    }

    public static function fromStoreRequest(AttendanceRequest $request): AttendanceDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            learningActivityId: $request->validated('learning_activity_id'),
            studentId: $request->validated('student_id'),
            isPresent: $request->validated('is_present'),
        );
    }

    public static function fromUpdateRequest(AttendanceRequest $request): AttendanceDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            learningActivityId: null,
            studentId: null,
            isPresent: $request->validated('is_present'),
        );
    }
}
