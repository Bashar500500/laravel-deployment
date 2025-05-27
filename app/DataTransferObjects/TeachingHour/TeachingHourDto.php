<?php

namespace App\DataTransferObjects\TeachingHour;

use App\Http\Requests\TeachingHour\TeachingHourRequest;
use App\Enums\TeachingHour\TeachingHourStatus;

class TeachingHourDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $instructorId,
        public readonly ?int $totalHours,
        public readonly ?int $completedHours,
        public readonly ?int $upcoming,
        public readonly ?int $break,
        public readonly ?TeachingHourStatus $status,
    ) {}

    public static function fromIndexRequest(TeachingHourRequest $request): TeachingHourDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            instructorId: null,
            totalHours: null,
            completedHours: null,
            upcoming: null,
            break: null,
            status: null,
        );
    }

    public static function fromStoreRequest(TeachingHourRequest $request): TeachingHourDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            instructorId: $request->validated('instructor_id'),
            totalHours: $request->validated('total_hours'),
            completedHours: $request->validated('completed_hours'),
            upcoming: $request->validated('upcoming'),
            break: $request->validated('break'),
            status: TeachingHourStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(TeachingHourRequest $request): TeachingHourDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            instructorId: null,
            totalHours: $request->validated('total_hours'),
            completedHours: $request->validated('completed_hours'),
            upcoming: $request->validated('upcoming'),
            break: $request->validated('break'),
            status: $request->validated('status') ?
                TeachingHourStatus::from($request->validated('status')) :
                null,
        );
    }
}
