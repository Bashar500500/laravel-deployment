<?php

namespace App\DataTransferObjects\Ticket;

use App\Http\Requests\Ticket\TicketRequest;
use Illuminate\Support\Carbon;
use App\Enums\Ticket\TicketPriority;
use App\Enums\Ticket\TicketStatus;

class TicketDto
{
    public function __construct(
        public readonly ?string $category,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?Carbon $date,
        public readonly ?string $subject,
        public readonly ?TicketPriority $priority,
        public readonly ?TicketStatus $status,
    ) {}

    public static function fromIndexRequest(TicketRequest $request): TicketDto
    {
        return new self(
            category: $request->validated('category'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            date: null,
            subject: null,
            priority: null,
            status: null,
        );
    }

    public static function fromStoreRequest(TicketRequest $request): TicketDto
    {
        return new self(
            category: $request->validated('category'),
            currentPage: null,
            pageSize: null,
            date: Carbon::parse($request->validated('date')),
            subject: $request->validated('subject'),
            priority: TicketPriority::from($request->validated('priority')),
            status: TicketStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(TicketRequest $request): TicketDto
    {
        return new self(
            category: $request->validated('category'),
            currentPage: null,
            pageSize: null,
            date: $request->validated('date') ?
                Carbon::parse($request->validated('date')) :
                null,
            subject: $request->validated('subject'),
            priority: $request->validated('priority') ?
                TicketPriority::from($request->validated('priority')) :
                null,
            status: $request->validated('status') ?
                TicketStatus::from($request->validated('status')) :
                null,
        );
    }
}
