<?php

namespace App\DataTransferObjects\Event;

use App\Http\Requests\Event\EventRequest;
use App\Enums\Event\EventType;
use App\Enums\Event\EventCategory;
use App\Enums\Event\EventRecurrence;
use Illuminate\Support\Carbon;

class EventDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?string $name,
        public readonly ?array $groups,
        public readonly ?EventType $type,
        public readonly ?Carbon $date,
        public readonly ?Carbon $startTime,
        public readonly ?Carbon $endTime,
        public readonly ?EventCategory $category,
        public readonly ?EventRecurrence $recurrence,
        public readonly ?string $description,
        public readonly ?EventAttachmentsDto $eventAttachmentsDto,
        ) {}

    public static function fromIndexRequest(EventRequest $request): EventDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            groups: null,
            type: null,
            date: null,
            startTime: null,
            endTime: null,
            category: null,
            recurrence: $request->validated('recurrence') ? EventRecurrence::from($request->validated('recurrence')) : null,
            description: null,
            eventAttachmentsDto: null,
        );
    }

    public static function fromStoreRequest(EventRequest $request): EventDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            name: $request->validated('name'),
            groups: $request->validated('groups'),
            type: EventType::from($request->validated('type')),
            date: Carbon::parse($request->validated('date')),
            startTime: Carbon::parse($request->validated('start_time')),
            endTime: Carbon::parse($request->validated('end_time')),
            category: EventCategory::from($request->validated('category')),
            recurrence: EventRecurrence::from($request->validated('recurrence')),
            description: $request->validated('description'),
            eventAttachmentsDto: EventAttachmentsDto::from($request),
        );
    }

    public static function fromUpdateRequest(EventRequest $request): EventDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            name: $request->validated('name'),
            groups: $request->validated('groups'),
            type: $request->validated('type') ?
                EventType::from($request->validated('type')) :
                null,
            date: $request->validated('date') ?
                Carbon::parse($request->validated('date')) :
                null,
            startTime: $request->validated('start_time') ?
                Carbon::parse($request->validated('start_time')) :
                null,
            endTime: $request->validated('end_time') ?
                Carbon::parse($request->validated('end_time')) :
                null,
            category: $request->validated('category') ?
                EventCategory::from($request->validated('category')) :
                null,
            recurrence: $request->validated('recurrence') ?
                EventRecurrence::from($request->validated('recurrence')) :
                null,
            description: $request->validated('description'),
            eventAttachmentsDto: EventAttachmentsDto::from($request),
        );
    }
}
