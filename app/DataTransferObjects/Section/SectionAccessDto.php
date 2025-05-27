<?php

namespace App\DataTransferObjects\Section;

use App\Http\Requests\Section\SectionRequest;
use Illuminate\Support\Carbon;

class SectionAccessDto
{
    public function __construct(
        public readonly ?Carbon $releaseDate,
        public readonly ?bool $hasPrerequest,
        public readonly ?bool $isPasswordProtected,
        public readonly ?string $password,
    ) {}

    public static function from(SectionRequest $request): SectionAccessDto
    {
        return new self(
            releaseDate: $request->validated('access.release_date') ?
                Carbon::parse($request->validated('access.release_date')) :
                null,
            hasPrerequest: $request->validated('access.has_prerequest'),
            isPasswordProtected: $request->validated('access.is_password_protected'),
            password: $request->validated('access.password'),
        );
    }
}
