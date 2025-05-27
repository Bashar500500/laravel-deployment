<?php

namespace App\DataTransferObjects\Holiday;

use App\Http\Requests\Holiday\HolidayRequest;
use App\Enums\Holiday\HolidayDay;
use Illuminate\Support\Carbon;

class HolidayDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?Carbon $date,
        public readonly ?HolidayDay $day,
    ) {}

    public static function fromIndexRequest(HolidayRequest $request): HolidayDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            date: null,
            day: null,
        );
    }

    public static function fromStoreRequest(HolidayRequest $request): HolidayDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            date: Carbon::parse($request->validated('date')),
            day: HolidayDay::from($request->validated('day')),
        );
    }

    public static function fromUpdateRequest(HolidayRequest $request): HolidayDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            date: $request->validated('date') ?
                Carbon::parse($request->validated('date')) :
                null,
            day: $request->validated('day') ?
                HolidayDay::from($request->validated('day')) :
                null,
        );
    }
}
