<?php

namespace App\DataTransferObjects\Section;

use App\Http\Requests\Section\SectionRequest;

class SectionResourcesDto
{
    public function __construct(
        public readonly ?array $files,
        public readonly ?array $links,
    ) {}

    public static function from(SectionRequest $request): SectionResourcesDto
    {
        return new self(
            files: $request->validated('resources.files'),
            links: $request->validated('resources.links'),
        );
    }
}
