<?php

namespace App\DataTransferObjects\SupportTicket;

use App\Http\Requests\SupportTicket\SupportTicketRequest;
use Illuminate\Support\Carbon;
use App\Enums\SupportTicket\SupportTicketPriority;
use App\Enums\SupportTicket\SupportTicketStatus;

class SupportTicketDto
{
    public function __construct(
        public readonly ?string $category,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?Carbon $date,
        public readonly ?string $subject,
        public readonly ?SupportTicketPriority $priority,
        public readonly ?SupportTicketStatus $status,
    ) {}

    public static function fromIndexRequest(SupportTicketRequest $request): SupportTicketDto
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

    public static function fromStoreRequest(SupportTicketRequest $request): SupportTicketDto
    {
        return new self(
            category: $request->validated('category'),
            currentPage: null,
            pageSize: null,
            date: Carbon::parse($request->validated('date')),
            subject: $request->validated('subject'),
            priority: SupportTicketPriority::from($request->validated('priority')),
            status: SupportTicketStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(SupportTicketRequest $request): SupportTicketDto
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
                SupportTicketPriority::from($request->validated('priority')) :
                null,
            status: $request->validated('status') ?
                SupportTicketStatus::from($request->validated('status')) :
                null,
        );
    }
}
