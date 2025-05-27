<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;
use Illuminate\Support\Carbon;

class LearningActivityAvailabilityDto
{
    public function __construct(
        public readonly ?Carbon $start,
        public readonly ?Carbon $end,
        public readonly ?string $timezone,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityAvailabilityDto
    {
        return new self(
            start: $request->validated('availability.start') ?
                Carbon::parse($request->validated('availability.start')) :
                null,
            end: $request->validated('availability.end') ?
                Carbon::parse($request->validated('availability.end')) :
                null,
            timezone: $request->validated('availability.timezone'),
        );
    }
}
