<?php

namespace App\DataTransferObjects\Leave;

use App\Http\Requests\Leave\LeaveRequest;
use App\Enums\Leave\LeaveType;
use App\Enums\Leave\LeaveStatus;
use Illuminate\Support\Carbon;

class LeaveDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?LeaveType $type,
        public readonly ?Carbon $from,
        public readonly ?Carbon $to,
        public readonly ?int $numberOfDays,
        public readonly ?string $reason,
        public readonly ?LeaveStatus $status,
    ) {}

    public static function fromIndexRequest(LeaveRequest $request): LeaveDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            type: null,
            from: null,
            to: null,
            numberOfDays: null,
            reason: null,
            status: null,
        );
    }

    public static function fromStoreRequest(LeaveRequest $request): LeaveDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            type: LeaveType::from($request->validated('type')),
            from: Carbon::parse($request->validated('from')),
            to: Carbon::parse($request->validated('to')),
            numberOfDays: $request->validated('number_of_days'),
            reason: $request->validated('reason'),
            status: LeaveStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(LeaveRequest $request): LeaveDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            type: $request->validated('type') ?
                LeaveType::from($request->validated('type')) :
                null,
            from: $request->validated('from') ?
                Carbon::parse($request->validated('from')) :
                null,
            to: $request->validated('to') ?
                Carbon::parse($request->validated('to')) :
                null,
            numberOfDays: $request->validated('number_of_days'),
            reason: $request->validated('reason'),
            status: $request->validated('status') ?
                LeaveStatus::from($request->validated('status')) :
                null,
        );
    }
}
