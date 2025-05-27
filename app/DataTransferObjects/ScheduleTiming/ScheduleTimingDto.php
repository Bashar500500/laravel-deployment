<?php

namespace App\DataTransferObjects\ScheduleTiming;

use App\Http\Requests\ScheduleTiming\ScheduleTimingRequest;
use Illuminate\Support\Carbon;

class ScheduleTimingDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $instructorId,
        public readonly ?int $courseId,
        public readonly ?array $instructorAvailableTimings,
    ) {}

    public static function fromIndexRequest(ScheduleTimingRequest $request): ScheduleTimingDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            instructorId: null,
            courseId: null,
            instructorAvailableTimings: null,
        );
    }

    public static function fromStoreRequest(ScheduleTimingRequest $request): ScheduleTimingDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            instructorId: $request->validated('instructor_id'),
            courseId: $request->validated('course_id'),
            instructorAvailableTimings: $request->validated('instructor_available_timings'),
        );
    }

    public static function fromUpdateRequest(ScheduleTimingRequest $request): ScheduleTimingDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            instructorId: null,
            courseId: null,
            instructorAvailableTimings: $request->validated('instructor_available_timings'),
        );
    }
}
