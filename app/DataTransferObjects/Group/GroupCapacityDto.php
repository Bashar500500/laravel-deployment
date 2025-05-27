<?php

namespace App\DataTransferObjects\Group;

use App\Http\Requests\Group\GroupRequest;

class GroupCapacityDto
{
    public function __construct(
        public readonly ?int $min,
        public readonly ?int $max,
    ) {}

    public static function from(GroupRequest $request): GroupCapacityDto
    {
        return new self(
            min: $request->validated('capacity.min'),
            max: $request->validated('capacity.max'),
        );
    }
}
