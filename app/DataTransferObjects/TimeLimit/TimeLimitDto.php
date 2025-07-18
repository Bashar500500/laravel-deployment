<?php

namespace App\DataTransferObjects\TimeLimit;

use App\Http\Requests\TimeLimit\TimeLimitRequest;
use App\Enums\TimeLimit\TimeLimitStatus;
use App\Enums\TimeLimit\TimeLimitType;

class TimeLimitDto
{
    public function __construct(
        public readonly ?int $instructorId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?TimeLimitStatus $status,
        public readonly ?int $durationMinutes,
        public readonly ?TimeLimitType $type,
        public readonly ?int $graceTimeMinutes,
        public readonly ?int $extensionTimeMinutes,
        public readonly ?array $settings,
        public readonly ?array $warnings,
    ) {}

    public static function fromIndexRequest(TimeLimitRequest $request): TimeLimitDto
    {
        return new self(
            instructorId: null,
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            description: null,
            status: null,
            durationMinutes: null,
            type: null,
            graceTimeMinutes: null,
            extensionTimeMinutes: null,
            settings: null,
            warnings: null,
        );
    }

    public static function fromStoreRequest(TimeLimitRequest $request): TimeLimitDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            status: TimeLimitStatus::from($request->validated('status')),
            durationMinutes: $request->validated('duration_minutes'),
            type: TimeLimitType::from($request->validated('type')),
            graceTimeMinutes: $request->validated('grace_time_minutes'),
            extensionTimeMinutes: $request->validated('extension_time_minutes'),
            settings: $request->validated('settings'),
            warnings: $request->validated('warnings'),
        );
    }

    public static function fromUpdateRequest(TimeLimitRequest $request): TimeLimitDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            status: $request->validated('status') ?
                TimeLimitStatus::from($request->validated('status')) :
                null,
            durationMinutes: $request->validated('duration_minutes'),
            type: $request->validated('type') ?
                TimeLimitType::from($request->validated('type')) :
                null,
            graceTimeMinutes: $request->validated('grace_time_minutes'),
            extensionTimeMinutes: $request->validated('extension_time_minutes'),
            settings: $request->validated('settings'),
            warnings: $request->validated('warnings'),
        );
    }
}
